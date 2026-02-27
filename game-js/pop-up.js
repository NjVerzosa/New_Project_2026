function isMobileDevice() {
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
}

window.onload = function () {
    // Check if the popup has already been shown and ensure it's not a mobile device
    // if (!isMobileDevice() && !localStorage.getItem('popupShown')) {

    if (!isMobileDevice()) {
        const style = document.createElement('style');
        style.innerHTML = `
            .custom-swal-popup {
                border-radius: 10px;
                padding: 15px 20px; /* Reduced padding for smaller height */
                background: linear-gradient(135deg, #ff9800, #f44336);
            }
            .custom-swal-content {
                font-size: 13px; /* Smaller font size for compactness */
                font-family: 'Verdana', sans-serif;
                color: #ffffff;
                line-height: 1.2; /* Adjust line height for better spacing */
            }
            .swal2-title {
                font-size: 18px; /* Smaller title font size */
                color: blue;
                font-weight: bold;
                text-shadow: 1px 1px 2px white;
            }
            .swal2-popup {
                box-shadow: 0px 8px 30px rgba(0, 0, 0, 0.2);
                height: auto; /* Ensures height adjusts based on content */
            }
        `;
        document.head.appendChild(style);

        Swal.fire({
            icon: 'info',
            title: 'EarningSphere says',
            text: 'This site is requires for mobile devices. Thank you for your support!',
            showConfirmButton: true,
            confirmButtonText: 'Got it!',
            width: 300,
            backdrop: `rgba(50,50,50,0.9)`,
            background: '#ffffff',
            customClass: {
                popup: 'custom-swal-popup',
                content: 'custom-swal-content'
            }
        }).then((result) => {
            if (result.isConfirmed || result.dismiss === Swal.DismissReason.backdrop) {
                // localStorage.setItem('popupShown', 'true');
                window.location.reload();
            }
        });
    }
};

// document.onkeydown = function (e) {
//     if (
//         e.ctrlKey &&
//         (e.keyCode === 67 || e.keyCode === 85 || e.keyCode === 73 || e.keyCode === 83)
//     ) {
//         return false;
//     } else {
//         return true;
//     }
// };

// (function () {
//     (function a() {
//         try {
//             (function b(i) {
//                 if (('' + (i / i)).length !== 1 || i % 20 === 0) {
//                     (function () { }).constructor('debugger')()
//                 } else {
//                     debugger
//                 }
//                 b(++i)
//             })(0)
//         } catch (e) {
//             setTimeout(a, 5000)
//         }
//     })()
// })();

// document.addEventListener('contextmenu', function (e) {
//     e.preventDefault(); // Disable right-click context menu
// });

// document.addEventListener('DOMContentLoaded', function () {
//     var noSelectElements = document.querySelectorAll('.noselect');
//     for (var i = 0; i < noSelectElements.length; i++) {
//         noSelectElements[i].ondragstart = function () {
//             return false;
//         };
//     }
// });


function detectAdBlocker() {
    setTimeout(() => {
        const adContainer = document.querySelector('.adsbygoogle');
        const adIframe = adContainer ? adContainer.querySelector('iframe') : null;

        const isAdBlocked = !adIframe || adIframe.offsetHeight === 0;

        if (isAdBlocked && !localStorage.getItem('adBlockerPopupShown')) {
            const style = document.createElement('style');
            style.innerHTML = `
                .custom-swal-popup {
                    border-radius: 10px;
                    padding: 15px 20px;
                    background: linear-gradient(135deg, #ff9800, #f44336);
                }
                .custom-swal-content {
                    font-size: 13px;
                    font-family: 'Verdana', sans-serif;
                    color: #ffffff;
                    line-height: 1.2;
                }
                .swal2-title {
                    font-size: 18px;
                    color: blue;
                    font-weight: 900;
                    text-shadow: 1px 1px 2px white;
                }
                .swal2-popup {
                    box-shadow: 0px 8px 30px rgba(0, 0, 0, 0.2);
                    height: auto;
                }
            `;
            document.head.appendChild(style);

            Swal.fire({
                icon: 'info',
                title: 'EarningSphere says',
                text: 'Ads help support our free content. Please consider disabling your ad blocker to support us. Thank you!',
                showConfirmButton: true,
                confirmButtonText: 'Got it!',
                width: 300,
                backdrop: `rgba(50,50,50,0.9)`,
                background: '#ffffff',
                customClass: {
                    popup: 'custom-swal-popup',
                    content: 'custom-swal-content'
                }
            }).then((result) => {
                if (result.isConfirmed || result.dismiss === Swal.DismissReason.backdrop) {
                    // Set a flag in localStorage to indicate the popup has been shown
                    localStorage.setItem('adBlockerPopupShown', 'true');
                }
            });
        }
    }, 5000); // Delay to allow ads to load
}

window.onload = detectAdBlocker;


document.addEventListener('DOMContentLoaded', function () {


    const sidebarToggle = document.querySelector("#sidebar-toggle");
    sidebarToggle.addEventListener("click", function () {
        document.querySelector("#sidebar").classList.toggle("collapsed");
    });

    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    document.querySelector(".scroll-to-top").addEventListener("click", (event) => {
        event.preventDefault();
        scrollToTop();
    });
});
