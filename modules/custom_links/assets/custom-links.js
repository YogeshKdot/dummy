$(document).ready(function(){
    "use strict";

    toggleMainSetupFields();
    toggleExternalInternalLink();
    toggleConvertHashWarning();
    toggleConvertHashWarning2();
    setHttpDropdown();

    const $addForm = $("#custom_links_form");

    if($addForm.length > 0) {
        _validate_form($($addForm),
            {
                title: "required",
                href: "required",
                position: "number",
            }
        );
    }

    if($("[name='users[]'].ajax-search").length > 0) {
        var params = {"roles": function(){
            return $("select.roles").val();
        }};
        init_ajax_search('staff', "[name='users[]'].ajax-search", params, admin_url + 'custom_links/filter_staff');
    }

    if($("[name='clients[]'].ajax-search").length > 0) {
        init_ajax_search('customers', "[name='clients[]'].ajax-search");
    }

    $('.icon-picker').iconpicker();

    $("[name=main_setup]").change(function(){
        toggleMainSetupFields();
    });

    $("[name=require_login]").change(function(){
        toggleClientsDropdown();
    });

    $("[name=external_internal]").change(function(){
        toggleExternalInternalLink();
    });

    $("#main_parent_id").change(function(){
        toggleConvertHashWarning();
    });

    $("#setup_parent_id").change(function(){
        toggleConvertHashWarning2();
    });

    $("[name=href]").on('change keyup', function(){
        var link = $(this).val();
        var result = link.replace(/(^\w+:|^)\/\//, '');
        $(this).val(result);
    });

    $(".form-field-roles").find("select").on("change", function(){
        $(".form-field-users").find("select").html('');
        $(".form-field-users").find("select").val('').selectpicker("refresh");
    });

    if($("#custom-links-iframe").length > 0){
        var height = $("#wrapper").innerHeight();
        $("#custom-links-iframe").attr("height", height+'px');
    }
});

function setMenuType(ele, i){
    $("[name=main_setup]").val(i);
    $(ele).addClass("active").siblings('a').removeClass('active');
    toggleMainSetupFields();
}

function setHttp(i){
    $("[name=http_protocol]").val(i);
    if(i == "0"){
        $(".mcl_http").html('http:// <span class="caret"></span>');
    }
    else{
        $(".mcl_http").html('https:// <span class="caret"></span>');
    }
    $("#external_link_prefix li").removeClass('active');
    $("#http_protocol_" + i ).closest("li").addClass('active');
}

function setHttpDropdown(){
    const http = $("[name=http_protocol]").val();
    setHttp(http);
}

function toggleClientsDropdown(){
    const require_login = $("[name=require_login]:checked").val(),
        $clientDropdown = $('.form-field-clients');
    if(require_login == "1"){
        $clientDropdown.removeClass('hide');
    }
    else{
        $clientDropdown.addClass('hide');
        $clientDropdown.find('select').selectpicker("val", "");
    }
}

function toggleExternalInternalLink(){
    var link = $("[name=external_internal]:checked").val(),
        $hrefField = $(".form_link").find("[name=href]"),
        currentValue = $hrefField.val();
    if((link == "0" || link == "1") && currentValue != "#"){
        $hrefField.data('value', currentValue);
    }
    var savedValue = $hrefField.attr("value"),
        updatedValue = $hrefField.data("value");
    if(link == "0"){
        $("#internal_link_prefix").removeClass('hide');
        $("#external_link_prefix").addClass('hide');
        $(".form_link").removeClass("hide");
        if(updatedValue != "")
            $hrefField.val(updatedValue);
        else if(savedValue == "")
            $hrefField.val("");
    }
    else if(link == "1"){
        $("#internal_link_prefix").addClass('hide');
        $("#external_link_prefix").removeClass('hide');
        $(".form_link").removeClass("hide");
        if(updatedValue != "")
            $hrefField.val(updatedValue);
        else if(savedValue == "")
            $hrefField.val("");
    }
    else{
        $(".form_link").addClass("hide");
        $hrefField.val("#");
    }
}

function toggleConvertHashWarning(){
    const i = $("#main_parent_id").val();
    const $e = $("#main_parent_id").closest('.form-group').find(".convert_hash_warning");
    if(i == ""){
        $e.addClass('hide');
    }
    else{
        $e.removeClass('hide');
    }
}

function toggleConvertHashWarning2(){
    const i = $("#setup_parent_id").val();
    const $e = $("#setup_parent_id").closest('.form-group').find(".convert_hash_warning");
    if(i == ""){
        $e.addClass('hide');
    }
    else{
        $e.removeClass('hide');
    }
}

function toggleMainSetupFields(){
    var main_setup = $("[name=main_setup]").val();
    var show_in_iframe = $("[name=show_in_iframe]:checked").val();
    var $icon_fields = $(".form-icon");
    var $blank_fields = $(".form-blank");
    var $form_field_roles = $(".form-field-roles");
    var $form_field_users = $(".form-field-users");
    var $form_field_badge = $(".form-field-badge");
    var $form_require_login = $(".form-require-login");
    var $main_menu_items = $(".main_menu_items");
    var $setup_menu_items = $(".setup_menu_items");

    if(main_setup == "0" || main_setup == "2")
        $icon_fields.removeClass('hide');
    else{
        $icon_fields.addClass('hide');
        $("#icon-new").val('').trigger('change');
    }

    if((main_setup == "0" || main_setup == "2") && show_in_iframe == "0")
        $blank_fields.removeClass('hide');
    else{
        $blank_fields.addClass('hide');
        $("#open_in_blank0").trigger('click');
    }

    if(main_setup == "0" || main_setup == "1"){
        $form_field_roles.removeClass('hide');
        $form_field_users.removeClass('hide');
    }
    else{
        $form_field_roles.addClass('hide');
        $form_field_roles.find("select").selectpicker("val", "");
        $form_field_users.addClass('hide');
        $form_field_users.find("select").html('');
        $form_field_users.find("select").selectpicker("val", "");
    }

    if(main_setup == "0" || main_setup == "1")
        $form_field_badge.removeClass('hide');
    else{
        $form_field_badge.addClass('hide');
        $form_field_badge.find('input').val('');
    }

    if(main_setup == "0" || main_setup == "1"){
        $form_require_login.addClass('hide');
        $("#require_login0").trigger('click');
    }
    else{
        $form_require_login.removeClass('hide');
    }
    toggleClientsDropdown();

    if(main_setup == "0" || main_setup == "1"){
        $form_field_badge.removeClass('hide');
    }
    else{
        $form_field_badge.addClass('hide');
        $form_field_badge.find('input').val('');
    }

    if(main_setup == "0"){
        $main_menu_items.removeClass('hide');
        $main_menu_items.find("select").removeAttr("disabled").selectpicker('refresh');
        $setup_menu_items.addClass('hide');
        $setup_menu_items.find("select").attr("disabled", "true").selectpicker('refresh');
    }
    else if(main_setup == "1"){
        $main_menu_items.addClass('hide');
        $main_menu_items.find("select").attr("disabled", "true").selectpicker('refresh');
        $setup_menu_items.removeClass('hide');
        $setup_menu_items.find("select").removeAttr("disabled").selectpicker('refresh');
    }
    else{
        $main_menu_items.addClass('hide');
        $main_menu_items.find("select").attr("disabled", "true").selectpicker('refresh');
        $setup_menu_items.addClass('hide');
        $setup_menu_items.find("select").attr("disabled", "true").selectpicker('refresh');
    }
}