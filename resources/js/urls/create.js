require('../apiClient');
import {alertMessage} from "../message";

export default $(function () {

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
                alertMessage(response.success, 'success', 'store');
            },
            error: function (response) {
                alertMessage(JSON.parse(response.responseText).error, 'danger', 'store');
            }
        })
    })
})