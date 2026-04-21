<meta name="theme-color" content="#eb812aff"/>
<link rel="manifest" href="/manifest.json">
<script>
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
      navigator.serviceWorker.register('/sw.js');
    });
  }
</script>
