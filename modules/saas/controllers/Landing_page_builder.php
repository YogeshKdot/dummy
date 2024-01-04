<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Landing_page_builder extends AdminController
{
    public $baseDir;
    public $themeBaseDir;
    public $themeBaseUrl;
    public $mediaDirectoryPath;
    public $mediaDirectoryUrl;
    public $allowedImageExtensions = ['ico', 'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];

    public function __construct()
    {
        parent::__construct();
        $this->app_modules->is_inactive('saas') ? access_denied() : '';

        $this->load->model('superadmin_model');
        $this->baseDir = module_dir_path(SUPERADMIN_MODULE, 'views/'); 

        $this->mediaDirectoryPath = module_dir_path(SUPERADMIN_MODULE, 'public/assets/media');
        $this->mediaDirectoryUrl = module_dir_url(SUPERADMIN_MODULE, 'public/assets/media');

        list($themePath, $themeUrl) = get_theme_path_url();
        $this->themeBaseDir = $themePath;
        $this->themeBaseUrl = $themeUrl;
	}

    public function builder()
    {
        $data['title'] = _l('perfex_saas_cms');

        $data['pages'] = get_landing_pages();
        $data['landingpagesBaseUrl'] = module_dir_url(SUPERADMIN_MODULE, 'views/');
        $data['mediaDirectoryUrl'] = $this->mediaDirectoryUrl;

        $data['isRTL'] = (is_rtl() ? 'true' : 'false');
        $data['controllerUrl'] = admin_url(SUPERADMIN_MODULE . '/landing_page_builder');
        $data['pageActionUrl'] = $data['controllerUrl'] . '/page';
        $data['themeBaseUrl'] = $this->themeBaseUrl;
        $data['builderAssetPath'] = module_dir_url(SUPERADMIN_MODULE, 'public/assets');

        $this->load->view('landing_page/builder', $data);
    }

    /**
     * Method to handle page actions.
     * It handly page copy, rename, save and delete.
     *
     * @param string $action The action to perform
     * @return void
     */
    public function page($action = '')
    {
        $fileSizeLimit = 1024 * 1024 * 2; //2 Megabytes max html file size

        $html   = '';
        $file   = '';
        $newfile = '';
        $startTemplateUrl = '';

        $formData = $this->input->post(null, false);
        if (isset($formData['startTemplateUrl']) && !empty($formData['startTemplateUrl'])) {
            $startTemplateUrl = $this->sanitizeFileName($formData['startTemplateUrl'], true);
            $html = file_get_contents($startTemplateUrl);
        } else if (isset($formData['html'])) {
            $html = substr($formData['html'], 0, $fileSizeLimit);
        }
        
        if (isset($formData['file'])) {
            $file = $this->sanitizeFileName($formData['file'], true);
        }

        if (isset($formData['newfile'])) {
            $newfile = $this->sanitizeFileName($formData['newfile'], true);
        }

        // restrict writing to the theme base dir only or the media folder
        $validFile = str_starts_with($file, $this->themeBaseDir) || str_starts_with($file, $this->mediaDirectoryPath);
        $validNewFile = empty($newfile) || str_starts_with($newfile, $this->themeBaseDir) || str_starts_with($newfile, $this->mediaDirectoryPath);

        if (!$validFile || !$validNewFile) {
            return $this->showError(_l('perfex_saas_builder_wrong_filepath', [$file . ' ' . $newfile]));
        }

        if ($action) {
            //file manager actions, delete and rename
            switch ($action) {
                case 'rename':
                    if ($file && $newfile) {
                        $duplicate = isset($formData['duplicate']) && (string)$formData['duplicate'] === 'true';
                        $actionMode = $duplicate ? _l('copied') : _l('renamed');
                        if (!$duplicate) {
                            if (!file_exists($file) || !rename($file, $newfile))
                                return $this->showError(_l('error_renaming_file', [$file, $newfile]));
                        } else {
                            $dir = dirname($newfile);
                            if (!is_dir($dir)) mkdir($dir, 0755, true);
                            
                            if (!file_exists($file) || !copy($file, $newfile))
                                return $this->showError(_l('errorcopying_file', [$file, $newfile]));
                        }

                        echo _l('file_action', [$file, $actionMode, $newfile]);
                        exit;
                    }
                    break;
                case 'delete':
                    if ($file) {

                        if (!file_exists($file) || !unlink($file)) {
                            return $this->showError(_l('error_deleting_file', [$file]));
                        }

                        // remove the directory also if no more files
                        $themePath = dirname($file);
                        $htmlFiles = glob('{' . $themePath . '/*\/*.html,' . $themePath . '/*.html}',  GLOB_BRACE);
                        if (empty($htmlFiles) && $this->themeBaseDir != $themePath) {
                            perfex_saas_remove_dir($themePath);
                        }

                        echo _l('file_deleted', [$file]);
                        exit;
                    }
                    break;
                default:
                    $this->showError(_l('invalid_action', [$action]));
            }
        } else {
            //save page
            if (!$html)
                return $this->showError(_l('html_content_empty'));

            if (!$file)
                return $this->showError(_l('filename_empty'));

            $dir = dirname($file);
            if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
                return $this->showError(_l('folder_not_exist', [$dir]));
            }

            //allow only .html extension here
            $pathInfo = pathinfo($file);
            if (empty($pathInfo['extension']) || $pathInfo['extension'] !== "html" || !str_starts_with($pathInfo['dirname'], $this->themeBaseDir))
                throw new \Exception("Error Processing Request", 1);

            $file = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . ".html";
            try {
                $html = $this->filterTemplateContent($html);

                if (!file_put_contents($file, $html))
                    return $this->showError(_l('error_saving_file', [$file]));

                // Copy the start template asset files
                if ($startTemplateUrl) {
                    $assetFolder = dirname($startTemplateUrl) . '/assets';
                    $destAssetFolder = $dir . '/assets';
                    if (is_dir($assetFolder) && !is_dir($destAssetFolder)) {
                        xcopy($assetFolder, $destAssetFolder);
                    }
                }
            } catch (\Throwable $th) {
                return $this->showError($th->getMessage());
            }
            echo _l('file_saved', [$file]);
        }
    }

    private function filterTemplateContent($html)
    {
        $dom = new DOMDocument();
        libxml_use_internal_errors(true); // dont throw error
        $dom->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD); // Load the HTML content
        libxml_clear_errors(); // clear errors if any

        $xpath = new DOMXPath($dom);

        // Add csrf token to all post submissions
        $forms = $xpath->query('//form[@method="post"]');

        foreach ($forms as $form) {

            $action = $form->getAttribute('action');
            if (empty($action) || str_starts_with($action, ["/", "#", base_url()])) {

                $inputExists = false;
                $inputElements = $xpath->query('.//input[@name="[csrf_token_name]"]', $form);

                if ($inputElements->length > 0) {
                    $inputExists = true;
                }

                if (!$inputExists) {
                    $input = $dom->createElement('input');
                    $input->setAttribute('type', 'hidden');
                    $input->setAttribute('name', '[csrf_token_name]');
                    $input->setAttribute('value', '[csrf_token_hash]');

                    $form->appendChild($input);
                }
            }
        }

        return $dom->saveHTML();
    }

     /**
     * Validate and sanitize file name.
     * It cleans and validate safe extension
     *
     * @param string $file
     * @param boolean $appendThemeDir
     * @return mixed
     */
    private function sanitizeFileName($file, $appendThemeDir = false)
    {
        //sanitize, remove double dot .. and remove get parameters if any
        $file = preg_replace('@\?.*$@', '', preg_replace('@\.{2,}@', '', preg_replace('@[^\/\\a-zA-Z0-9\-\._]@', '', $file)));
        $extension  = pathinfo($file, PATHINFO_EXTENSION);

        if ($appendThemeDir) {

            if ($extension === 'html') {
                $file = $this->themeBaseDir . str_ireplace('//', '/', '/' . $file);
            } else {
                // media files. Must start with media dir ul
                if (str_starts_with($file, $this->mediaDirectoryUrl))
                    $file = str_ireplace($this->mediaDirectoryUrl, $this->mediaDirectoryPath, $file);
            }
        }
        
        // check if extension is on allow list
        if ($extension !== 'html' && !in_array($extension, $this->allowedImageExtensions)) {
            return $this->showError(_l('file_not_allowed', [$extension]));
        }

        return $file;
    }

    /**
     * Display error with 500 header
     *
     * @param string $error
     * @return void
     */
    private function showError($error)
    {
        set_status_header(500, $error);
        echo $error;
        exit;
    }

    /**
     * Scan media folder for all media files to be display in builder media modal.
     *
     * @return void
     */
    public function media_scan()
    {
        $scandir = $this->mediaDirectoryPath;

        // Run the recursive function
        // This function scans the files folder recursively, and builds a large array
        $scan = function ($dir) use ($scandir, &$scan) {
            $files = [];

            // Is there actually such a folder/file?

            if (file_exists($dir)) {
                foreach (scandir($dir) as $f) {
                    if (!$f || $f[0] == '.') {
                        continue; // Ignore hidden files
                    }

                    if (is_dir($dir . '/' . $f)) {
                        // The path is a folder

                        $files[] = [
                            'name'  => $f,
                            'type'  => 'folder',
                            'path'  => str_replace($scandir, '', $dir) . '/' . $f,
                            'items' => $scan($dir . '/' . $f), // Recursively get the contents of the folder
                        ];
                    } else {
                        // It is a file

                        $files[] = [
                            'name' => $f,
                            'type' => 'file',
                            'path' => str_replace($scandir, '', $dir) . '/' . $f,
                            'size' => filesize($dir . '/' . $f), // Gets the size of this file
                        ];
                    }
                }
            }
            return $files;
        };
        $response = $scan($scandir);

        // Output the directory listing as JSON

        header('Content-type: application/json');
        echo json_encode([
            'name'  => '',
            'type'  => 'folder',
            'path'  => '',
            'items' => $response,
        ]);
        exit;
    }

    /**
     * Handle file upload from the media modal
     *
     * @return void
     */
    public function media_upload()
    {
        $fileName  = $_FILES['file']['name'];
        $extension = strtolower(substr($fileName, strrpos($fileName, '.') + 1));

        // check if extension is on allow list
        if (!in_array($extension, $this->allowedImageExtensions)) {
            return $this->showError(_l('perfex_saas_builder_file_not_allowed', [$extension]));
        }

        if (!is_dir($this->mediaDirectoryPath) && !mkdir($this->mediaDirectoryPath, 0755, true))
            return $this->showError(_l('perfex_saas_builder_error_creating_folder', [$this->mediaDirectoryPath]));

        try {
            $destination = $this->sanitizeFileName($this->mediaDirectoryPath . '/' . $fileName);
            if (!move_uploaded_file($_FILES['file']['tmp_name'], $destination))
                return $this->showError(_l('perfex_saas_builder_file_not_uploaded', [$destination]));
        } catch (\Throwable $th) {
            return $this->showError($th->getMessage());
        }

        if ($this->input->post('onlyFilename', true)) {
            echo $fileName;
        } else {
            echo $destination;
        }
    }
}

/* End of file Landing_page.php */