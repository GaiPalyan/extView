require('./bootstrap');
require('./ajaxSetup');


/*$(function () {
    $('#index').on('click', function (e) {
        e.preventDefault();

        $.ajax({
            type: "GET",
            url: $(this).attr('href'),
            dataType: 'json',
            success: function (response) {
                $.each(response.urls, function (item) {
                    /!*$('#id').html(url.id)
                    $('#name').html(url.name)*!/
                })
            }
        })
    })
})*/


$(function () {

    $('#url_store').on('submit', function (event) {
        event.preventDefault();
        let name = $("#my_url").val();
        let data = {
            "name": name
        };

        $.ajax({
            url:$(this).attr('action'),
            method:$(this).attr('method'),
            data: JSON.stringify(data),
            success: function (response) {
                console.log(response)
            },
            error: function (error) {
                console.log(data);
            }
        })
    })
})
