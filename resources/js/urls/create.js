import {apiClient} from "../apiClient";

export default $(function () {

    $('#url_store').on('submit', function (event) {
        event.preventDefault();
        let data = {
            "name": $("#my_url").val(),
        };

        let apiClientParam = {
            "endpoint": $(this).attr('action'),
            "method": $(this).attr('method'),
            "data": JSON.stringify(data),
            "action": 'store',
        };

        apiClient(apiClientParam);
    })
})