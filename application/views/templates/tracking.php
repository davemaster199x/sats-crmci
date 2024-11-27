<?php if(ENVIRONMENT == 'production' && !empty(config_item('google_tag'))): ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= config_item('google_tag'); ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?= config_item('google_tag'); ?>');
    </script>
<?php endif; ?>
<meta name="robots" content="noindex,follow" />
