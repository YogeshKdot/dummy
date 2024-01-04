function loadGridView() {
    $(".header-user-profile").on("click", function() {
        $(".header-user-profile").toggleClass("open")
    });
    var a = {
        search: $("input#search").val(),
        start: 0,
        length: _lnth,
        draw: 1,
        order: [{
            column: 0,
            dir: 'desc'
        }]
    };
    gridViewDataCall(a, function(b) {
        $("div.grid_panel").html(b)
    });
}

function openSettingDropdown(a) {
    $(a).find(".dropdown").toggleClass("open")
}

function gridViewDataCall(a, b, c) {
    $.ajax({
        url: admin_url + "idea_hub/challenge_grid/" + (a.start + 1),
        method: "POST",
        data: a,
        async: true,
        error: function(d, m, h) {
            console.log("error API", h)
        },
        beforeSend: function() {},
        complete: function() {},
        success: function(d) {
            $.isFunction(b) && b.call(this, d)
        }
    });
}

function loadIdeasGridView() {
    $(".header-user-profile").on("click", function() {
        $(".header-user-profile").toggleClass("open")
    });
    var a = {
        search: $("input#search").val(),
        start: 0,
        length: _lnth,
        draw: 1,
        challenge_id: $("input#challenge_id").val(),
        order: [{
            column: 7,
            dir: 'desc'
        }]
    };
    ideasGridViewDataCall(a, function(b) {
        $("div#grid-tab").html(b)
    });
}

function ideasGridViewDataCall(a, b, c) {
    $.ajax({
        url: admin_url + "idea_hub/ideas_grid/" + (a.start + 1),
        method: "POST",
        data: a,
        async: true,
        error: function(d, m, h) {
            console.log("error API", h)
        },
        beforeSend: function() {},
        complete: function() {},
        success: function(d) {
            $.isFunction(b) && b.call(this, d)
        }
    });
}

function idea_kanban_update(a, b) {
    if (b === a.item.parent()[0]) {
        var c = {};
        c.stage_id = $(a.item.parent()[0]).data('task-stage-id');
        c.idea_id = $(a.item).data('concept-id');
        setTimeout(function() {
            $.post(admin_url + "idea_hub/update_idea_stage_kanban", c).done(function(d) {
                check_kanban_empty_col("[data-task-status-id]")
            })
        }, 200)
    }
}
var hidden_columns = [];

function archivedChallenge(a) {
    $.get(admin_url + "idea_hub/archivedChallenge/" + (void 0 === a ? "" : a), function(b) {
        
    });
}
$(document).ready(function() {
    $("input[name=visibility]").click(function() {
        "custom" == $(this).val() ? $("#clientid-dropdown").removeClass("hidden") : $("#clientid-dropdown").addClass("hidden")
    });
   // archivedChallenge();

    tinymce.init({
        selector: '.contentTextArea',
        plugins: 'lists',
        toolbar: 'numlist bullist',
        branding: false
    });
});

function openChallengeModal() {
    $("#challenge-modal").modal("show")
}