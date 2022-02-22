import {apiClient} from "../apiClient";
require('./search');

export default $(function () {
    $(document).ready(function () {
        apiClient('/api/urls', 'GET').done(function (data) {
            data.forEach(item => $('table').append(createTable(item)));
        })
    })
})

export const createTable = (url) => {
    return `<tr>
                <td>${url.id}</td>
                <td><a href="urls/${url.id}">${url.name}</a></td>
                <td>${url.last_check ?? ''}</td>
                <td>${url.status_code ?? ''}</td>
            </tr>`;
}