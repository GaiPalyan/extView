export default $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    contentType:'application/json',
    dataType: "json",
});

export const apiClient = (endpoint, method) => {
    return $.ajax({
        url: endpoint,
        method: method,
    });
}