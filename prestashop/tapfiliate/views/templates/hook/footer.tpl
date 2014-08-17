<script src="//tapfiliate.com/tapfiliate.js" type="text/javascript" async></script>
<script type="text/javascript">
  window['TapfiliateObject'] = i = 'tap';
  window[i] = window[i] || function () {
      (window[i].q = window[i].q || []).push(arguments);
  };

  tap('create', '{$tapfiliate_id|escape:'htmlall':'UTF-8'}');
  {if $isOrder eq true}
  tap('transaction', '{$trans.id|escape:'htmlall':'UTF-8'}', {$trans.total|escape:'htmlall':'UTF-8'});
  {else}
  tap('detectClick');
  {/if}
</script>
