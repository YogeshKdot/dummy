<?php

defined('BASEPATH') or exit('No direct script access allowed');
include( __DIR__ . '/../vendor/autoload.php');
use Orhanerday\OpenAi\OpenAi;
class Aiwriter_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_default_usage_case(): array
    {
        return [
            'product_description'   => 'Product Description',
            'brand_name'            => 'Brand Name',
            'email'                 => 'Email',
            'email_reply'           => 'Email Reply',
            'review_feedback'       => 'Review Feedback',
            'blog_idea'             => 'Blog Idea &amp; Outline',
            'blog_writing'          => 'Blog Section Writing',
            'business_idea'         => 'Business Ideas',
            'business_idea_pitch'   => 'Business Idea Pitch',
            'proposal_later'        => 'Proposal Later',
            'cover_letter'          => 'Cover Letter',
            'call-to_action'        => 'Call to Action',
            'job_description'       => 'Job Description',
            'legal_agreement'       => 'Legal Agreement',
            'social_ads'            => 'Facebook, Twitter, Linkedin Ads',
            'google_ads'            => 'Google Search Ads',
            'post_idea'             => 'Post &amp; Caption Ideas',
            'police_general_dairy'  => 'Police General Dairy',
            'comment_reply'         => 'Comment Reply',
            'birthday_wish'         => 'Birthday Wish',
            'seo_meta'              => 'SEO Meta Description',
            'seo_title'             => 'SEO Meta Title',
            'song_lyrics'           => 'Song Lyrics',
            'story_plot'            => 'Story Plot',
            'review'                => 'Review',
            'testimonial'           => 'Testimonial',
            'video_des'             => 'Video Description',
            'video_idea'            => 'Video Idea',
            'php_code'              => 'PHP Code',
            'python_code'           => 'Python Code',
            'java_code'             => 'Java Code',
            'javascript_code'       => 'Javascript Code',
            'dart_code'             => 'Dart Code',
            'swift_code'            => 'Swift Code',
            'c_code'                => 'C Code',
            'c#_code'               => 'C# Code',
            'mysql_query'           => 'MySQL Query',
            'mssql_query'           => 'MSSQL Query',
        ];
    }

    public function reset_usage_case()
    {
        $this->db->truncate(db_prefix() . 'aiwriter_usage_cases');
        $i = 0;
        foreach($this->get_default_usage_case() as $key=>$value):
            $uc_data['usage_case_key'] = $key;
            $uc_data['usage_case'] = $value;
            ($i == 7) ? $uc_data['is_default'] = 1: $uc_data['is_default'] = 0;
            $this->db->insert(db_prefix() . 'aiwriter_usage_cases',$uc_data);
            $i++;
        endforeach;
        return true;
    }

    public function get_all_usage_case_db(): array
    {
        $usage_cases = [];
        $this->db->select('usage_case,usage_case_key');
        $this->db->where('status',1);
        $results = $this->db->get(db_prefix() . 'aiwriter_usage_cases')->result_array();
        foreach($results as $row):
            array_push($usage_cases,array($row['usage_case_key'] =>$row['usage_case']));
        endforeach;
        return $usage_cases;
        //return $this->db->get(db_prefix() . 'aiwriter_usage_cases')->result_array();
    }

    public function get_usage_case_as_array(): array
    {
        $this->db->where('status',1);
        return $this->db->get(db_prefix() . 'aiwriter_usage_cases')->result_array();
    }


    public function get_ajax_ai_content($option=array()){
        $apiKey             = get_option('aiwriter_openai_api_key');
        $limitText          = get_option('aiwriter_openai_limit_text');
        $usage_caseList     = $this->get_all_usage_case_db();
        $prompt = "Write ";
        $prompt .= $option['numberVariant']." ";
        $prompt .= str_replace("_"," ",$option['usage_case']);
        $prompt .= " About ";
        $prompt .= $option['keyword'];
        //var_dump($prompt); exit();

        $openAi = new OpenAi($apiKey);

        $result = $openAi->completion([
            'model'       => 'text-davinci-003',
            'prompt'      => $prompt,
            'max_tokens'  => (int)$limitText,
            'temperature' => 0
        ]);
        $result = json_decode($result, true);
        $text = '';
        if (array_key_exists("choices",$result)):
            foreach ($result['choices'] as $choice):
                $text .= $choice['text'];
            endforeach;
            return ltrim($text ?? '');
        else:
            return false;
        endif;

    }


}
