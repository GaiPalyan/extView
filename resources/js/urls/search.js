import {apiClient} from "../apiClient";
import {createTable} from "./index";

export default $(function () {
    $("#search").on('submit', function (event) {
        event.preventDefault();
        const searchInput = $("#search_param").val();
        const endpoint = [$(this).attr('action'),`?field=${searchInput}`].join('');

        let apiClientParam = {
            "endpoint": endpoint,
            "method": 'GET',
        };

        apiClient(apiClientParam).done(function (data) {

            let resultHTML = '';
            for (const [key, value] of Object.entries(data)) {
                resultHTML += createTable(value);
            }
            $('tbody').html(resultHTML);
        });
    });
});