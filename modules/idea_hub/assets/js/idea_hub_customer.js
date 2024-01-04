function loadGridForChallenge() {
    var a = { search: $("input#search").val(), start: 0, length: _lnth, draw: 1 };
    challengeGridViewDataCall(a, function (b) {
        $("div#grid-tab").html(b);
    });
}
function challengeGridViewDataCall(a, b, e) {
    $.ajax({
        url: site_url + "idea_hub/client_challenges/grid/" + (a.start + 1),
        method: "POST",
        data: a,
        async: true,
        error: function (c, f, d) {
            console.log("error API", d);
        },
        beforeSend: function () {},
        complete: function () {},
        success: function (c) {
            $.isFunction(b) && b.call(this, c);
        },
    });
}
function loadIdeasGridView() {
    $(".header-user-profile").on("click", function () {
        $(".header-user-profile").toggleClass("open");
    });
    var a = { search: $("input#search").val(), start: 0, length: _lnth, draw: 1, challenge_id: $("input#challenge_id").val() };
    ideasGridViewDataCall(a, function (b) {
        $("div#grid-tab").html(b);
    });
}
function ideasGridViewDataCall(a, b, e) {
    $.ajax({
        url: site_url + "idea_hub/client_ideas/ideas_grid/" + (a.start + 1),
        method: "POST",
        data: a,
        async: !1,
        error: function (c, f, d) {
            console.log("error API", d);
        },
        beforeSend: function () {},
        complete: function () {},
        success: function (c) {
            $.isFunction(b) && b.call(this, c);
        },
    });
}
function openChallengeModal() {
    $("#challenge-modal").modal("show");
}
function openSettingDropdown(a) {
    $(a).find(".dropdown").toggleClass("open")
}
$(document).ready(function () {
    $("input[name=visibility]").click(function () {
        "custom" == $(this).val() ? $("#clientid-dropdown").removeClass("hidden") : $("#clientid-dropdown").addClass("hidden");
    });

    if (window.hasOwnProperty('tinymce')) {
        tinymce.init({
            selector: '.contentTextArea',
            plugins: 'lists',
            toolbar: 'numlist bullist',
            branding: false
        });

        $('.tagsinput').tagsinput({
            allowDuplicates: true
        });

        $('.tagsinput').on('itemAdded', function(item, tag) {
            $('.items').html('');
            var tags = $('.tagsinput').tagsinput('items');
            $.each(tags, function(index, tag) {
                $('.items').append('<span>' + tag + '</span>');
            });
        });
    }

    $("input[name='cover_type']").on('change',function(){
        if($(this).val()=='video'){
            $("#video_thumbnail").closest('div').removeClass('hidden');
        }else{
            $("#video_thumbnail").closest('div').addClass('hidden');
        }
    });
});