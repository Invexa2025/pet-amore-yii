(function($) {
    // Default Bootstrap Table settings
    $.fn.bootstrapTable.defaults = {
        classes: 'table',
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
        method: 'POST',
        sidePagination: 'server',
        pagination: true,
        buttonsClass: 'secondary btn-sm',
        pageSize: 50,
        pageList: [10, 25, 50, 75, 100]
    };

    /**
     * Creates custom pagination for Bootstrap Table
     * @param {string} tableId - The ID of the table to add pagination to
     */
    $.makePagination = function(tableId) {
        const $div = $(tableId).parents('.bootstrap-table');
        const tablePaginationId = `pagination_${tableId.substring(1)}`;
        
        const paginationHTML = `
            <div id="${tablePaginationId}" class="bootstrap-table-pagination d-flex align-items-center justify-content-between p-2 bg-light rounded float-right">
                <div class="d-flex align-items-center">
                    <button id="btnRefreshTable" class="btn btn-sm btn-secondary me-2" title="Refresh">
                        <i class="bi bi-arrow-repeat"></i>
                    </button>
                    <div class="d-flex align-items-center me-3">
                        <span class="me-2">Page</span>
                        <input type="number" min="1" class="form-control form-control-sm text-pagination-change" 
                            style="width: 60px; text-align: center;">
                        <span class="ms-2">of <span class="fw-bold displayAllPage"></span></span>
                    </div>
                    <div class="text-nowrap me-3">
                        Showing <span class="fw-bold displayFirst"></span> - 
                        <span class="fw-bold displayLast"></span> of 
                        <span class="fw-bold displayAll"></span> rows
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="btn-group btn-pager me-2">
                            <button class="btn btn-sm btn-secondary prev-page" title="Previous Page">
                                <i class="bi bi-chevron-left"></i>
                            </button>
                            <button class="btn btn-sm btn-secondary next-page" title="Next Page">
                                <i class="bi bi-chevron-right"></i>
                            </button>
                        </div>
                        <div class="pagination-size">
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        $div.prepend(paginationHTML);

        $(tableId).on('post-body.bs.table', function(data) {
            const options = $(tableId).bootstrapTable('getOptions');
            const $pagination = $(`#${tablePaginationId}`);
            const lastPage = options.totalPages;

            // Update pagination controls
            $pagination.find('.text-pagination-change').val(options.pageNumber);
            $pagination.find('.prev-page').prop('disabled', options.pageNumber === 1);
            $pagination.find('.next-page').prop('disabled', options.pageNumber === lastPage || options.data.length === options.totalRows);

            // Update display values
            const displayFirst = options.data.length === 0 ? 0 : (options.pageNumber - 1) * options.pageSize + 1;
            const displayLast = Math.min(options.pageNumber * options.pageSize, options.totalRows);
            
            $pagination.find('.displayFirst').text(displayFirst);
            $pagination.find('.displayLast').text(displayLast);
            $pagination.find('.displayAll').text(options.totalRows);
            $pagination.find('.displayAllPage').text(options.totalPages);

            // Handle page size dropdown
            const pageSizeHtml = $div.find('.page-list').children('.btn-group').clone(true);
            const $pageSize = $pagination.find('.pagination-size');
            
            if (options.totalRows > 10) {
                pageSizeHtml.removeClass('dropup')
                    .find('.dropdown-toggle').addClass('button-xsm').end()
                    .find('.dropdown-menu').addClass('dropdown-menu-right');
                $pageSize.html(pageSizeHtml);
            } else {
                $pageSize.empty();
            }

            $pagination.removeClass('d-none');

            // Fix page size element dropdown
            $pageSize.off('click').on('click', function() {
                const $this = $(this);
                $this.toggleClass('clicked', !$this.hasClass('clicked'));
                
                if ($this.hasClass('clicked')) {
                    const $pageSizeText = $this.find('.page-size');
                    const activeSize = $this.find('.active').text();
                    if (activeSize) $pageSizeText.text(activeSize);
                }
            });
        });

        // Event handlers
        $div.find('#btnRefreshTable').click(() => $(tableId).bootstrapTable('refresh'));
        $div.find('.prev-page').click(() => $(tableId).bootstrapTable('prevPage'));
        $div.find('.next-page').click(() => $(tableId).bootstrapTable('nextPage'));
        $div.find('.text-pagination-change').keypress(function(e) {
            if (e.which === 13) {
                $(tableId).bootstrapTable('selectPage', $(this).val());
            }
        });
    };

    /**
     * Creates a global search component
     * @param {Object} param - Configuration options
     */
    $.globalSearch = function(param) {
        const defaults = {
            disabled: false,
            placeholder: 'Search',
            searchOnEnter: true,
            useDropdown: false,
            useButton: true,
            dropdownHtml: '',
            title: 'Advanced Search',
            globalTextId: '__txtGlobalSearch__',
            globalTextLength: null,
            modalOptions: {
                backdrop: 'static',
                show: false
            },
            buttonHtml: null,
            searchFunction: () => undefined,
            searchFunctionAdvanced: () => undefined,
            afterRender: () => undefined
        };
        
        const opts = $.extend(true, {}, defaults, param);
        const $searchDiv = $('#__divGlobalSearch__');
        let searchHTML = '';
        const styleTxtSearch = opts.useDropdown || opts.useButton ? '' : 'right-half-circle';

        // Build search HTML
        if (opts.useDropdown || opts.useButton) {
            $searchDiv.addClass('input-group');
            
            if (opts.useButton) {
                searchHTML += `
                    <div class="input-group-prepend">
                        <button type="button" class="form-control form-control-sm btn btn-sm btn-primary" id="__btnSearch__">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                `;
            }
        }

        // Add input field
        searchHTML += `
            <input type="text" class="form-control form-control-sm data-parsley-required ${styleTxtSearch}" 
                placeholder="${opts.placeholder}" id="${opts.globalTextId}" 
                ${opts.globalTextLength ? `maxlength="${opts.globalTextLength}"` : ''}>
        `;

        // Add dropdown button if needed
        if (opts.useDropdown) {
            searchHTML += `
                <div class="input-group-append">
                    <button type="button" class="form-control form-control-sm btn btn-sm btn-secondary" id="__btnGlobalSearch__">
                        <i class="bi bi-chevron-down"></i>
                    </button>
                </div>
            `;
            
            // Create modal for advanced search
            if (!opts.buttonHtml) {
                opts.buttonHtml = `
                    <div>
                        <button type="button" class="btn btn-sm btn-secondary toolbar-btn" id="_btnLayoutModalReset">
                            <i class="bi bi-arrow-counterclockwise"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-sm btn-primary toolbar-btn" id="_btnLayoutModalSearch" form="_formAdvancedSearch_">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>
                `;
            }

            $('body').append(`
                <div class="modal fade" id="_modalLayoutSearch_" aria-hidden="true" aria-labelledby="modalSearchTitle" data-backdrop="static">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title font-weight-bold">${opts.title}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="_formAdvancedSearch_" class="form" autocomplete="off"></form>
                            </div>
                            <div class="modal-footer">
                                ${$(opts.buttonHtml).remove().html()}
                            </div>
                        </div>
                    </div>
                </div>
            `);

            const $modal = $('#_modalLayoutSearch_').modal(opts.modalOptions);
            $modal.find('#_formAdvancedSearch_').html(opts.dropdownHtml);

            // Initialize Parsley validation
            $('#_formAdvancedSearch_').parsley({
                errorClass: 'is-invalid text-danger',
                errorsWrapper: '<span class="form-text text-danger"></span>',
                errorTemplate: '<span></span>',
                trigger: 'change'
            });

            // Form submission handler
            $('#_formAdvancedSearch_').submit(function(e) {
                e.preventDefault();
                opts.searchFunctionAdvanced();
                $modal.modal('hide');
            });

            // Reset button handler
            $('#_btnLayoutModalReset').click(function() {
                $('#_formAdvancedSearch_').trigger('reset');
                $('#_formAdvancedSearch_').parsley().reset();
                $('.selectpicker').selectpicker('refresh');
            });
        }

        $searchDiv.html(searchHTML);
        $(`#${opts.globalTextId}`).prop('disabled', opts.disabled);

        // Event handlers
        $('#__btnGlobalSearch__').click(() => {
            $('.has-error').find('.help-block').remove().end().removeClass('has-error');
            $('#_modalLayoutSearch_').modal('show');
        });

        if (opts.searchOnEnter) {
            $('#__txtGlobalSearch__').keypress(function(e) {
                if (e.which === 13) {
                    $('#__btnSearch__').click();
                    return false;
                }
            });
        }

        if (opts.useButton) {
            $('#__btnSearch__').click(opts.searchFunction);
        }

        opts.afterRender();
    };

    /**
     * Shows loading indicator
     * @param {Object} param - Configuration options
     */
    $.loading = function(param) {
        const overlayImg = $('#overlay-img').data('overlay-image');
        
        if (overlayImg) {
            $.LoadingOverlay('show', {
                image: overlayImg,
                background: 'rgba(0, 0, 0, 0.5)',
                maxSize: '200px',
                imageAnimation: ""
            });
        } else {
            const opts = $.extend({
                text: 'Loading'
            }, param || {});
            
            $('body').append(`
                <div class="overlay" id="__overlay_load__"></div>
                <div id="__loader__">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped active" style="width:100%">
                            ${opts.text}
                        </div>
                    </div>
                </div>
            `);
        }
    };

    /**
     * Hides loading indicator
     */
    $.unloading = function() {
        const overlayImg = $('#overlay-img').data('overlay-image');
        overlayImg ? $.LoadingOverlay("hide") : $('#__loader__, #__overlay_load__').remove();
    };

    /**
     * Creates service day input checkboxes
     * @param {string} divId - The ID of the container div
     */
    $.createInputServiceDay = function(divId) {
        const days = [
            { id: 1, day: 'MON' },
            { id: 2, day: 'TUE' },
            { id: 3, day: 'WED' },
            { id: 4, day: 'THU' },
            { id: 5, day: 'FRI' },
            { id: 6, day: 'SAT' },
            { id: 7, day: 'SUN' }
        ];
        
        const html = days.map(day => `
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="chk${day.id}">
                <label class="mt-5 font-weight-bold" for="chk${day.id}" style="margin-left:-6px !important;">
                    ${day.id}
                </label>
                <br>
                <label class="fs-9 font-weight-bold" for="chk${day.id}" style="margin-left:-20px !important;">
                    (${day.day})
                </label>
            </div>
        `).join('');
        
        $(divId).html(html).addClass('d-flex justify-content-between');
    };

    /**
     * Creates a digital clock
     * @param {string} attr - The ID of the HTML element to display the clock in
     */
    $.digitalClockSkeleton = function(attr) {
        const WEEK = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
        const MONTH = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

        function updateTime() {
            const now = new Date();
            const timezoneOffset = now.getTimezoneOffset();
            const timezoneOffsetHours = Math.abs(Math.floor(timezoneOffset / 60));
            const timezoneOffsetMinutes = Math.abs(timezoneOffset % 60);
            const timezoneSign = timezoneOffset < 0 ? '+' : '-';

            document.getElementById(attr).innerText =
                `${WEEK[now.getDay()]}, ${zeroPadding(now.getDate(), 2)} ` +
                `${MONTH[now.getMonth()]} ${now.getFullYear()} ` +
                `${zeroPadding(now.getHours(), 2)}:${zeroPadding(now.getMinutes(), 2)}:${zeroPadding(now.getSeconds(), 2)} ` +
                `${timezoneSign}${zeroPadding(timezoneOffsetHours, 2)}${zeroPadding(timezoneOffsetMinutes, 2)}`;
        }

        function zeroPadding(num, digit) {
            return String(num).padStart(digit, '0');
        }

        updateTime();
        setInterval(updateTime, 1000);
    };

    /**
     * Creates breadcrumb navigation
     * @param {string} breadcrumb - The breadcrumb path (e.g., "home/page/subpage")
     */
    $.breadcrumb = function(breadcrumb) {
        const bc = breadcrumb.split('/');
        const lastIndex = bc.length - 1;
        
        const html = bc.map((item, index) => {
            const text = initCap(item.replace(/-/g, ' '));
            return index === lastIndex
                ? `<li class="breadcrumb-item active">${text}</li>`
                : `<li class="breadcrumb-item"><a href="#">${text}</a></li>`;
        }).join('&ensp;');

        $('#breadcumb-menu-title').html(`<h3>${initCap(bc[lastIndex].replace(/-/g, ' '))}</h3>`);
        $('#breadcrumb').html(html);
    };
})(jQuery);

// Global AJAX handlers
$(document).ajaxStart(() => $.loading());
$(document).ajaxStop(() => $.unloading());

/**
 * Converts newlines to <br> tags
 * @param {string} str - The input string
 * @param {boolean} is_xhtml - Whether to use XHTML style <br /> tags
 * @returns {string} The converted string
 */
function nl2br(str, is_xhtml) {
    if (typeof str === 'undefined' || str === null) return '';
    const breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return str.toString().replace(/(\r\n|\n\r|\r|\n)/g, breakTag);
}

/**
 * Formats error messages from response
 * @param {Object} response - The response object
 * @returns {string} The formatted error message
 */
function msgConverter(response) {
    let output = '';

    if (typeof response.errStr === 'object' && response.errStr !== null) {
        for (const key in response.errStr) {
            if (Object.hasOwnProperty.call(response.errStr, key)) {
                output += response.errStr[key];
            }
        }
    } else {
        output += response.errStr;
    }

    return response.errNum !== 0 ? output : '';
}

// Global AJAX error handler
$(document).ajaxError(function(event, xhr) {
    if (xhr.status !== 0 && xhr.status !== 302) {
        const title = xhr.responseText === 'No HTML/Script injection allowed!'
            ? 'Warning!'
            : `${xhr.status} - ${xhr.statusText}`;
            
        const html = xhr.responseText === 'No HTML/Script injection allowed!'
            ? `<span style="color:red;">${xhr.responseText}</span>`
            : xhr.responseText;

        BootstrapModalWrapperFactory.alert({
            title: xhr.status + ' - ' + xhr.statusText,
            message: xhr.responseText
        }).updateSize('modal-lg');;
    }
});

/**
 * Formatter that pads the left side of a value with asterisks
 * @param {string} value - The input value
 * @returns {string} The formatted value
 */
function padLeftFormatter(value) {
    return value.substring(value.length - 4).padStart(value.length, '*');
}

/**
 * Formatter that displays a running number
 * @param {*} value - Not used
 * @param {Object} row - The row data
 * @param {number} index - The row index
 * @returns {string} The formatted running number
 */
function runningFormatter(value, row, index) {
    return `${row.rnum}.`;
}

/**
 * Capitalizes the first letter of each word in a string
 * @param {string} str - The input string
 * @returns {string} The capitalized string
 */
function initCap(str) {
    return str.toLowerCase().replace(/\b\w/g, char => char.toUpperCase());
}

/**
 * Formatter that converts value to lowercase
 * @param {string} value - The input value
 * @returns {string} The lowercase value or original if null
 */
function lowerCaseFormatter(value) {
    return value?.toLowerCase() ?? value;
}

/**
 * Formatter that converts value to uppercase
 * @param {string} value - The input value
 * @returns {string} The uppercase value or original if null
 */
function upperCaseFormatter(value) {
    return value?.toUpperCase() ?? value;
}