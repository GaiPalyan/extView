import {alertSuccess} from "../message";
import {getEntity} from "./show";
import __ from "lodash";

export default $(function () {

    $('#check').on('submit', function (event) {
        event.preventDefault();
        let url = "http://0.0.0.0:8000/api/urls";
        let endpoint = 'check';

        $.ajax({
            url: [url, JSON.parse(getEntity()).id, endpoint].join('/'),
            method: $(this).attr('method'),
            success: function (response) {
                $('#alert').append(alertSuccess(response));
                createCheckInfoRow(response.latest);
            }
        });
    });
});

const createCheckInfoRow = (latest) => {
    $('#check_results').append(
        `<tr>
            <td>${latest.id}</td>
            <td>${latest.status_code}</td>
            <td>${__.truncate(latest.h1, {"length": 50}) ?? ''}</td>
            <td>${__.truncate(latest.keywords, {"length": 50}) ?? ''}</td>
            <td>${__.truncate(latest.description, {"length": 50}) ?? ''}</td>
            <td>${latest.created_at}</td>
        </tr>`
    )
};