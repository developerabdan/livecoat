<section class="accordion">
    {{ $slot }}
</section>
<script>
    (() => {
        const accordions = document.querySelectorAll(".accordion");
        accordions.forEach((accordion) => {
            accordion.addEventListener("click", (event) => {
                const summary = event.target.closest("summary");
                if (!summary) return;
                const details = summary.closest("details");
                if (!details) return;
                accordion.querySelectorAll("details").forEach((detailsEl) => {
                    if (detailsEl !== details) {
                        detailsEl.removeAttribute("open");
                    }
                });
            });
        });
    })();
</script>
