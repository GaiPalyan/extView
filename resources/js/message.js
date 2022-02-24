import __ from "lodash";

export const alertMessage = (response, action) => {
    const messageStatus = __.last(Object.keys(response))
    const alertType = messageStatus === 'error' ? 'danger' : 'success';
    const message = response[messageStatus];
    return $('#alert').html(`
          <div class="alert-message ${action}">
            <div class="alert alert-${alertType}" role="alert">
                ${message}
                <button type="button"
                        class="close"
                        data-dismiss="alert"
                        aria-hidden="true"
                >&times;</button>
            </div>
          </div>`
    )
}