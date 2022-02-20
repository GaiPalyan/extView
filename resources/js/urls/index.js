require('../ajaxSetup');
require('./search');

export default $(function () {
    $(document).ready(function () {
        $.ajax({
            type: "GET",
            url: 'api/urls',
        }).done(function (data) {
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