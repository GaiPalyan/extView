import {apiClient} from "../apiClient";
import {createTable} from "./index";

export default $(function () {
    $("#search").on('submit', function (event) {
        event.preventDefault();
        const searchInput = $("#search_param").val();
        const endpoint = [$(this).attr('action'),`?field=${searchInput}`].join('');
        apiClient(endpoint, $(this).attr('method')).done(function (data) {

            let resultHTML = '';
            for (const [key, value] of Object.entries(data)) {
                resultHTML += createTable(value);
            }
            $('tbody').html(resultHTML);
        });
    });
});