import {apiClient} from "../apiClient";
import __ from "lodash";
import {alertMessage} from "../message";
import {getEntityId} from "./show";

export default $(function () {
    $('#check').on('submit', function (event) {
        event.preventDefault();
        let route = '/api/urls';
        let endpoint = [route, getEntityId(), 'check'].join('/')
        apiClient(endpoint, $(this).attr('method')).done(function (response) {
            alertMessage(response.success, 'success', 'check');
            createCheckInfoRow(response.latest);
        });
    });
});

export const createCheckInfoRow = (latest) => {
    $('#check_results').append(
        `<tr>
            <td>${latest.id}</td>
            <td>${latest.status_code}</td>
            <td>${__.truncate(latest.h1, {'length': 50}) ?? ''}</td>
            <td>${__.truncate(latest.keywords, {'length': 50}) ?? ''}</td>
            <td>${__.truncate(latest.description, {'length': 50}) ?? ''}</td>
            <td>${latest.created_at}</td>
        </tr>`
    )
};