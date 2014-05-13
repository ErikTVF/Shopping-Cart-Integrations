<script type="text/javascript">
    var _tap = _tap || {};
    _tap.account = '{$tapfiliate_id|escape:'htmlall':'UTF-8'}';
    {if $isOrder eq true}
    _tap.transaction_id = '{$trans.id|escape:'htmlall':'UTF-8'}';
    _tap.transaction_ammount = {$trans.total|escape:'htmlall':'UTF-8'};
    {/if}
    (function() {
        var tapjs = document.createElement('script'); tapjs.type = 'text/javascript'; tapjs.async = true;
        tapjs.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'tapfiliate.com/tap.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(tapjs, s);
    })();
</script>
