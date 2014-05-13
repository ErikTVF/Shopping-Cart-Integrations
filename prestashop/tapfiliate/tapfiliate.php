<?php

if (!defined('_PS_VERSION_'))
	exit;

class Tapfiliate extends Module
{
	public function __construct()
	{
		$this->name = 'tapfiliate';
		$this->tab = 'advertising_marketing';
		$this->version = '1.0';
		$this->author = 'Tapfiliate';
		$this->displayName = 'Tapfiliate';
		$this->module_key = 'fd2aaefea84ac1bb512e6f1878d990bj';

		parent::__construct();

		if ($this->id && !Configuration::get('TAPFILIATE_ID'))
			$this->warning = $this->l('You have not yet set your Tapfiliate Customer ID');
		$this->description = $this->l('Integrate the Tapfiliate tracking script in your shop');
		$this->confirmUninstall = $this->l('Are you sure you want to delete Tapfiliate from your shop?');
	}

	public function install()
	{
		return (parent::install() && $this->registerHook('footer') && $this->registerHook('orderConfirmation'));
	}

	public function getContent()
	{
		$output = '<h2>Tapfiliate</h2>';
		if (Tools::isSubmit('submitTap'))
		{
			Configuration::updateValue('TAPFILIATE_ID', Tools::getValue('tapfiliate_id'));
			$output .= '
			<div class="conf confirm">
				<img src="../img/admin/ok.gif" alt="" title="" />
				'.$this->l('Settings updated').'
			</div>';
		}

		return $output.$this->displayForm();
	}

	public function displayForm()
	{
		$output = '
		<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post">
			<fieldset class="width2">
				<legend><img src="../img/admin/cog.gif" alt="" class="middle" />'.$this->l('Settings').'</legend>
				<label>'.$this->l('Your Tapfiliate customer ID').'</label>
				<div class="margin-form">
					<input type="text" name="tapfiliate_id" value="'.Tools::safeOutput(Tools::getValue('tapfiliate_id', Configuration::get('TAPFILIATE_ID'))).'" />
					<p class="clear">'.$this->l('Example:').' 1-123abc</p>
				</div>
				<center><input type="submit" name="submitTap" value="'.$this->l('Update ID').'" class="button" /></center>
			</fieldset>
		</form>';

		return $output;
	}

	public function hookFooter($params)
	{
		if(!$this->context->smarty->getTemplateVars('isOrder')) {
			$this->context->smarty->assign('tapfiliate_id', Configuration::get('TAPFILIATE_ID'));
			$this->context->smarty->assign('isOrder', false);

			return $this->display(__FILE__, 'views/templates/hook/footer.tpl');
		}
	}

	public function hookOrderConfirmation($params)
	{
		// Setting parameters
		$parameters = Configuration::getMultiple(array('PS_LANG_DEFAULT'));

		$order = $params['objOrder'];
		if (Validate::isLoadedObject($order))
		{
			$conversion_rate = 1;

			// Order general information
			$trans = array(
				'id' => (int)$order->id,
				'total' => Tools::ps_round((float)$order->total_paid / (float)$conversion_rate, 2),
			);

			$tapfiliate_id = Configuration::get('TAPFILIATE_ID');

			$this->context->smarty->assign('trans', $trans);
			$this->context->smarty->assign('tapfiliate_id', $tapfiliate_id);
			$this->context->smarty->assign('isOrder', true);

			return $this->display(__FILE__, 'views/templates/hook/footer.tpl');
		}
	}
}
