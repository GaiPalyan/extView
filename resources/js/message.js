export const alertMessage = (message, type, action) => {
    return $('#alert').html(`
          <div class="alert-message ${action}">
            <div class="alert alert-${type}" role="alert">
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