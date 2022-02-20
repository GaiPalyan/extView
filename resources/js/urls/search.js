import {createTable} from "./index";

export default $(function () {
    $("#search").on('submit', function (event) {
        event.preventDefault();
        const searchInput = $("#search_param").val();
        const url = [$(this).attr('action'),`?field=${searchInput}`].join('');
        $.ajax({
            url:url,
            method: "GET",
        }).done(function (data) {
            data.forEach(item => $('tbody').replaceWith(`<tbody>${createTable(item)}</tbody>`));
        });
    });
});