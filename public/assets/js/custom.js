$(document).ready(function () {
    $("div[data-includeHTML]").each(function () {
        $(this).load($(this).attr("data-includeHTML"));
    });
});

$(document).ready(function () {
    $('#index').show('fast');
    $('#login').hide('fast');
    $('#login').hide('fast');
});

function showLoginScreen() {
    $('#index').hide('fast');
    $('#login').show('fast');
}

function showIndexScreen() {
    $('#index').show('fast');
    $('#login').hide('fast');
}

function logout() {
    self.location = "/logout"
}