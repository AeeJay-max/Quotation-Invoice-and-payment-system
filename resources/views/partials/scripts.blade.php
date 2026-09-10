<!-- jQuery -->
<script src="{{asset('assets/plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<!-- Bootstrap 4 -->
<script src="{{asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

<script src="{{asset('assets/plugins/select2/js/select2.full.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{asset('assets/plugins/daterangepicker/moment.min.js')}}"></script>
<script src="{{asset('assets/plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Summernote -->
<script src="{{asset('assets/plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{asset('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('assets/admin/js/adminlte.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{asset('assets/admin/js/demo.js')}}"></script>

<script type="text/javascript">
$(document).ready(function () {
    $('.alert').fadeIn().delay(5000).fadeOut();
    if ($.fn.select2) {
        $('.select2').select2();
    }
});

/* Sidebar Event Context AJAX Switcher */
function switchEventContext(selectElem) {
    const form = selectElem.form;
    const formData = new FormData(form);
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(res => {
        loadPageContent(window.location.href, false);
    })
    .catch(err => {
        form.submit();
    });
}

/* Sidebar Navigation Scroll Restoration */
document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.querySelector('.main-sidebar .sidebar');
    if (sidebar) {
        const savedScroll = sessionStorage.getItem('sidebar_scroll_top');
        if (savedScroll !== null) {
            sidebar.scrollTop = parseInt(savedScroll, 10);
        } else {
            const activeNav = sidebar.querySelector('.nav-link.active');
            if (activeNav) {
                activeNav.scrollIntoView({ block: 'center', behavior: 'instant' });
            }
        }

        sidebar.addEventListener('scroll', function () {
            sessionStorage.setItem('sidebar_scroll_top', sidebar.scrollTop);
        });

        sidebar.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                sessionStorage.setItem('sidebar_scroll_top', sidebar.scrollTop);
            });
        });
    }
});

/* Seamless PJAX Navigation Engine: Prevents Page Refreshes When Clicking Sidebar Links */
function loadPageContent(url, pushState = true) {
    const contentWrapper = document.querySelector('.content-wrapper');
    if (!contentWrapper) {
        window.location.href = url;
        return;
    }

    const sidebar = document.querySelector('.main-sidebar .sidebar');
    if (sidebar) {
        sessionStorage.setItem('sidebar_scroll_top', sidebar.scrollTop);
    }

    contentWrapper.style.transition = 'opacity 0.15s ease';
    contentWrapper.style.opacity = '0.4';

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('HTTP ' + response.status);
        return response.text();
    })
    .then(html => {
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');

        const newContent = doc.querySelector('.content-wrapper');
        const newModals = doc.querySelector('#app-modals');
        const appModals = document.querySelector('#app-modals');
        const newTitle = doc.querySelector('title');

        if (newContent) {
            contentWrapper.innerHTML = newContent.innerHTML;
            contentWrapper.style.opacity = '1';

            if (newModals && appModals) {
                appModals.innerHTML = newModals.innerHTML;
            }
            
            if (newTitle) {
                document.title = newTitle.innerText;
            }

            if (pushState) {
                history.pushState({ url: url }, '', url);
            }

            // Restore sidebar scroll position
            if (sidebar) {
                const savedScroll = sessionStorage.getItem('sidebar_scroll_top');
                if (savedScroll !== null) {
                    sidebar.scrollTop = parseInt(savedScroll, 10);
                }
            }

            // Update Active Link State in Sidebar
            const currentPath = new URL(url, window.location.origin).pathname;
            document.querySelectorAll('.main-sidebar .nav-link').forEach(link => {
                const href = link.getAttribute('href');
                if (href && href !== '#' && !href.startsWith('javascript')) {
                    const linkPath = new URL(href, window.location.origin).pathname;
                    if (currentPath === linkPath || (linkPath !== '/' && currentPath.startsWith(linkPath))) {
                        link.classList.add('active');
                    } else {
                        link.classList.remove('active');
                    }
                }
            });

            // Execute Inline Scripts Safely without crashing PJAX engine
            const scripts = newContent.querySelectorAll('script');
            scripts.forEach(s => {
                try {
                    if (s.src) {
                        const newScript = document.createElement('script');
                        newScript.src = s.src;
                        document.body.appendChild(newScript);
                    } else if (s.textContent.trim()) {
                        // Scope inline scripts to prevent identifier re-declaration syntax errors
                        (new Function(s.textContent))();
                    }
                } catch (err) {
                    console.warn('Notice executing inline script:', err);
                }
            });

            // Clean up modal backdrops and states on page load
            if (window.jQuery) {
                $('.modal').modal('hide');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open').css('padding-right', '');
                if ($.fn.select2) {
                    $('.select2').select2();
                }
                $('.alert').fadeIn().delay(5000).fadeOut();
            }

        } else {
            window.location.href = url;
        }
    })
    .catch(err => {
        console.warn('PJAX load fallback notice:', err);
        window.location.href = url;
    });
}

document.addEventListener('click', function(e) {
    const link = e.target.closest('.main-sidebar a');
    if (!link) return;

    const href = link.getAttribute('href');
    if (!href || href === '#' || href.startsWith('javascript') || link.hasAttribute('data-toggle')) {
        return;
    }

    if (link.closest('form')) {
        return;
    }

    if (href.startsWith('http') && !href.includes(window.location.hostname)) {
        return;
    }

    e.preventDefault();
    loadPageContent(href);
});

window.addEventListener('popstate', function(e) {
    if (e.state && e.state.url) {
        loadPageContent(e.state.url, false);
    } else {
        loadPageContent(window.location.href, false);
    }
});
</script>
