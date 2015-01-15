<?php
class InvoiceController extends Zend_Controller_Action
{
	function preDispatch()
	{
		$auth = Zend_Auth::getInstance();
		if (!$auth->hasIdentity()) {
		        $this->_redirect('/user/login');
		} else {
			$authns = new Zend_Session_Namespace($auth->getStorage()->getNamespace());
			$this->view->identity = $auth->getIdentity();
			$authns->setExpirationSeconds(60*60);
		}
	}

	public function indexAction()
	{
		$this->_redirect('/');
	}

	public function createAction()
	{
		$invoiceModel = new Application_Model_Invoices();
		if ($this->_request->isPost()) {
			$item_list = "";
			$qty_list = "";
			$price_list = "";
			$this->view->invoice_name = $this->_request->getPost('invoice_name');
			$this->view->invoice_addr1 = $this->_request->getPost('invoice_addr1');
			$this->view->invoice_addr2 = $this->_request->getPost('invoice_addr2');
			$this->view->invoice_city = $this->_request->getPost('invoice_city');
			$this->view->invoice_state = $this->_request->getPost('invoice_state');
			$this->view->invoice_zip = $this->_request->getPost('invoice_zip');

			$this->view->invoice_shipping = $this->_request->getPost('invoice_shipping');
			$this->view->invoice_adj = $this->_request->getPost('invoice_adj');
			$this->view->invoice_desc1 = $this->_request->getPost('invoice_desc1');
			$this->view->invoice_qty1 = $this->_request->getPost('invoice_qty1');
			$this->view->invoice_price1 = $this->_request->getPost('invoice_price1');

			// this section need HEAVY refactoring to scale properly

			if ($this->view->invoice_desc1 != "" && $this->view->invoice_qty1 != "" && $this->view->invoice_price1 != "") {
				$item_list .= $this->view->invoice_desc1."\n";
				$qty_list .= $this->view->invoice_qty1."\n";
				$price_list .= $this->view->invoice_price1."\n";
			}

			$this->view->invoice_desc2 = $this->_request->getPost('invoice_desc2');
			$this->view->invoice_qty2 = $this->_request->getPost('invoice_qty2');
			$this->view->invoice_price2 = $this->_request->getPost('invoice_price2');

			if ($this->view->invoice_desc2 != "" && $this->view->invoice_qty2 != "" && $this->view->invoice_price2 != "") {
				$item_list .= $this->view->invoice_desc2."\n";
				$qty_list .= $this->view->invoice_qty2."\n";
				$price_list .= $this->view->invoice_price2."\n";
			}

			$this->view->invoice_desc3 = $this->_request->getPost('invoice_desc3');
			$this->view->invoice_qty3 = $this->_request->getPost('invoice_qty3');
			$this->view->invoice_price3 = $this->_request->getPost('invoice_price3');

			if ($this->view->invoice_desc3 != "" && $this->view->invoice_qty3 != "" && $this->view->invoice_price3 != "") {
				$item_list .= $this->view->invoice_desc3."\n";
				$qty_list .= $this->view->invoice_qty3."\n";
				$price_list .= $this->view->invoice_price3."\n";
			}

			$this->view->invoice_desc4 = $this->_request->getPost('invoice_desc4');
			$this->view->invoice_qty4 = $this->_request->getPost('invoice_qty4');
			$this->view->invoice_price4 = $this->_request->getPost('invoice_price4');

			if ($this->view->invoice_desc4 != "" && $this->view->invoice_qty4 != "" && $this->view->invoice_price4 != "") {
				$item_list .= $this->view->invoice_desc4."\n";
				$qty_list .= $this->view->invoice_qty4."\n";
				$price_list .= $this->view->invoice_price4."\n";
			}

			$this->view->invoice_desc5 = $this->_request->getPost('invoice_desc5');
			$this->view->invoice_qty5 = $this->_request->getPost('invoice_qty5');
			$this->view->invoice_price5 = $this->_request->getPost('invoice_price5');

			if ($this->view->invoice_desc5 != "" && $this->view->invoice_qty5 != "" && $this->view->invoice_price5 != "") {
				$item_list .= $this->view->invoice_desc5."\n";
				$qty_list .= $this->view->invoice_qty5."\n";
				$price_list .= $this->view->invoice_price5."\n";
			}

			$this->view->invoice_desc6 = $this->_request->getPost('invoice_desc6');
			$this->view->invoice_qty6 = $this->_request->getPost('invoice_qty6');
			$this->view->invoice_price6 = $this->_request->getPost('invoice_price6');

			if ($this->view->invoice_desc6 != "" && $this->view->invoice_qty6 != "" && $this->view->invoice_price6 != "") {
				$item_list .= $this->view->invoice_desc6."\n";
				$qty_list .= $this->view->invoice_qty6."\n";
				$price_list .= $this->view->invoice_price6."\n";
			}

			$this->view->message = "";
			if (empty($this->view->invoice_name)) {
				$this->view->message .= '<p>Please provide a name for this customer.</p>';
			}
			if (empty($this->view->invoice_desc1)) {
				$this->view->message .= '<p>Please provide a description for the line item.</p>';
			}
			if (empty($this->view->invoice_qty1)) {
				$this->view->message .= '<p>Please provide a quantity for the line item.</p>';
			}
			if (empty($this->view->invoice_price1)) {
				$this->view->message .= '<p>Please provide a price for the line item.</p>';
			}
			if (preg_match("/[^0-9\.]/", $this->view->invoice_adj)) {
				$this->view->message .= '<p>Adjustments must only include numbers and a decimal.</p>';
			}
			if (preg_match("/[^0-9\.]/", $this->view->invoice_shipping)) {
				$this->view->message .= '<p>Shipping cost must only include numbers and a decimal.</p>';
			}

			if ($this->view->message == "") {
				$id = $invoiceModel->addInvoice(
					$this->view->invoice_name,
					$this->view->invoice_addr1,
					$this->view->invoice_addr2,
					$this->view->invoice_city,
					$this->view->invoice_state,
					$this->view->invoice_zip,
					$item_list,
					$qty_list,
					$price_list,
					$this->view->invoice_shipping,
					$this->view->invoice_adj,
					"OPEN");
				$this->_redirect('/invoice/view/'.$id);
			}
		}
	}

	public function listAction()
	{
		$invoiceModel = new Application_Model_Invoices();
		$invoiceArray = $invoiceModel->getAllInvoices();
		$this->view->invoices = $invoiceArray;
	}

	public function viewAction()
	{
		if ($this->getRequest()->getParam('id') == NULL) {
			$this->_redirect('/customer/list');
			return;
		}
		setlocale(LC_MONETARY, 'en_US');
		$id = $this->getRequest()->getParam('id');
		$invoiceModel = new Application_Model_Invoices();
		$invoiceData = $invoiceModel->getInvoice($id);

		// Create new PDF
		require('Zend/Pdf.php');
		$pdf = new Zend_Pdf();

		// Add new page to the document
		$page = $pdf->newPage(Zend_Pdf_Page::SIZE_LETTER);
		$pdf->pages[] = $page;

		$width  = $page->getWidth();
		$height = $page->getHeight();

		$fontReg = Zend_Pdf_Font::fontWithPath(APPLICATION_PATH."/../library/SourceSansPro-Regular.ttf");
		$fontBold = Zend_Pdf_Font::fontWithPath(APPLICATION_PATH."/../library/SourceSansPro-Bold.ttf");
		$fontSemibold = Zend_Pdf_Font::fontWithPath(APPLICATION_PATH."/../library/SourceSansPro-Semibold.ttf");

		// Set font
		$page->setFont($fontReg, 20);

		// Draw text
		$config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/application.ini');

		$tmpCompanyName = explode(' ',$config->companyName);
		$firstCompanyName = array_shift($tmpCompanyName);
		$lastCompanyName = implode(' ', $tmpCompanyName);

		$page->drawText($firstCompanyName, 50, 750);


		$page->setFont(Zend_Pdf_Font::fontWithPath(APPLICATION_PATH."/../library/SourceSansPro-Bold.ttf"), 25);
		$page->drawText($lastCompanyName, 123, 750);

		$page->saveGS();
		$page->rotate(0, 0, 0.07);

		// Draw rectangle
		$page->setLineColor(Zend_Pdf_Color_Html::color("#CC69A9"));
		$page->setFillColor(Zend_Pdf_Color_Html::color("#CC69A9"));
		$page->drawRectangle(0, 650, 405, 675);

		$page->setLineColor(Zend_Pdf_Color_Html::color("#fcb040"));
		$page->setFillColor(Zend_Pdf_Color_Html::color("#fcb040"));
		$page->drawRectangle(0, 621, 355, 646);

		$page->setLineColor(Zend_Pdf_Color_Html::color("#63CDF5"));
		$page->setFillColor(Zend_Pdf_Color_Html::color("#63CDF5"));
		$page->drawRectangle(0, 592, 305, 617);

		$page->setFont(Zend_Pdf_Font::fontWithPath(APPLICATION_PATH."/../library/SourceSansPro-Bold.ttf"), 18);

		$page->setFillColor(Zend_Pdf_Color_Html::color("#ffffff"));
		$page->drawText($config->companyEmail, 100, 656);

		$page->setFillColor(Zend_Pdf_Color_Html::color("#ffffff"));
		$page->drawText("Creative. Personal. Awesome.", 100, 627);

		$page->setFillColor(Zend_Pdf_Color_Html::color("#ffffff"));
		$page->drawText($config->companyPhone, 100, 598);

		$page->restoreGS();

		$page->setFont($fontReg, 56);
		$page->drawText("Invoice", 390, 620);

		$page->setFont($fontReg, 16);

		$page->setFillColor(Zend_Pdf_Color_Html::color("#a8a8a8"));
		$textWidth = $this->getTextWidth($invoiceData->created_ts, $fontReg, 16);
		$this->drawCenteredText($page, $invoiceData->created_ts, 392, 600, $textWidth, 170);

		$page->setFillColor(Zend_Pdf_Color_Html::color("#000000"));
		$page->setFont($fontBold, 20);
		$page->drawText($invoiceData->name, 50, 540);

		$pos = 520;

		if (!empty($invoiceData->addr1)) {
			$page->setFillColor(Zend_Pdf_Color_Html::color("#000000"));
			$page->setFont($fontBold, 20);
			$page->drawText($invoiceData->addr1, 50, $pos);

			$pos -= 20;
		}

		if (!empty($invoiceData->addr2)) {
			$page->setFillColor(Zend_Pdf_Color_Html::color("#000000"));
			$page->setFont($fontBold, 20);
			$page->drawText($invoiceData->addr2, 50, $pos);

			$pos -= 20;
		}

		if ($invoiceData->city != "" && $invoiceData->state != "") {
			$page->setFillColor(Zend_Pdf_Color_Html::color("#000000"));
			$page->setFont($fontBold, 20);
			$page->drawText($invoiceData->city.", ".$invoiceData->state." ".$invoiceData->zip, 50, $pos);
		}

		$page->setFillColor(Zend_Pdf_Color_Html::color("#00AEF5"));
		$page->setFont($fontReg, 13);
		$page->drawText("Invoice Number:", 390, 540);

		$page->setFillColor(Zend_Pdf_Color_Html::color("#000000"));
		$page->setFont($fontBold, 13);
		$page->drawText($invoiceData->id, 485, 540);

		$page->setFillColor(Zend_Pdf_Color_Html::color("#00AEF5"));
		$page->setFont($fontBold, 15);
		$page->drawText("DESCRIPTION", 50, 450);
		$page->drawText("QUANTITY", 325, 450);
		$page->drawText("PRICE", 420, 450);
		$page->drawText("SUBTOTAL", 480, 450);


		$items = explode("\n", $invoiceData->item_list);
		$qtys = explode("\n", $invoiceData->qty_list);
		$rates = explode("\n", $invoiceData->rate_list);
		$rowPos = 445;
		$rowCt = 2;
		$total = 0;
		for($i = 0; $i < sizeof($items)-1; $i++) {
			if (($rowCt % 2) == 0) {
				$page->setLineColor(Zend_Pdf_Color_Html::color("#dddddd"));
				$page->setFillColor(Zend_Pdf_Color_Html::color("#dddddd"));
				$page->drawRectangle(50, $rowPos, 555, ($rowPos-20));
			}

			$page->setFont($fontReg, 15);
			$page->setFillColor(Zend_Pdf_Color_Html::color("#000000"));
			$page->drawText($items[$i], 60, ($rowPos-15));
			$page->drawText($qtys[$i], 325, ($rowPos-15));
			$page->drawText('$'.money_format("%n",$rates[$i]), 420, ($rowPos-15));
			$page->drawText('$'.money_format("%n",($qtys[$i] * $rates[$i])), 480, ($rowPos-15));

			$total += ($qtys[$i] * $rates[$i]);
			$rowPos -= 20;
			$rowCt++;
		}

		$subtotal = $total;
		$total -= $invoiceData->adjustment;
		$total += $invoiceData->shipping;

		$page->setFillColor(Zend_Pdf_Color_Html::color("#00AEF5"));
		$page->drawText("SUBTOTAL", 263, 200);
		$page->drawText("SHIPPING", 269, 180);
		$page->drawText("ADJUSTMENTS", 235.5, 160);
		$page->drawText("TAXES", 290, 140);
		$page->drawText("TOTAL", 289, 120);

		$page->setFillColor(Zend_Pdf_Color_Html::color("#000000"));
		$page->drawText('$'.money_format("%n", $subtotal), 335, 200);
		$page->drawText('$'.money_format("%n", sprintf("%0.02f",$invoiceData->shipping)), 335, 180);
		$page->drawText('$'.money_format("%n", sprintf("%0.02f",$invoiceData->adjustment)), 335, 160);
		$page->drawText("\$0.00", 335, 140);
		$page->setFont($fontBold, 18);
		$page->drawText('$'.money_format("%n", $total), 335, 120);

		$pdf->pages[0] = ($page);
                $this->_helper->layout()->disableLayout();
		$this->getResponse()
                        ->setHeader('Content-Type', 'application/pdf')
                        ->setHeader('Content-Disposition', 'inline; filename="'.$invoiceData->id.'"-Invoice.pdf"')
                        ->setBody($pdf->render())
                        ->sendResponse();
		exit;
	}

	function drawCenteredText($page, $text, $x, $y, $textWidth, $areaWidth) {
		$areaWidth = $areaWidth / 2;
		$textWidth = $textWidth / 2;
		$x = $x + ($areaWidth - $textWidth);
		$page->drawText($text, $x, $y);
	}


	// code taken from http://www.phparty.com/3296_7761611/ - but not totally working...
	function getTextWidth($text, $font, $font_size) {
		$drawing_text = iconv('', 'UTF-8', $text);
		$characters    = array();
		for ($i = 0; $i < strlen($drawing_text); $i++) {
			$characters[] = (ord($drawing_text[$i++]) << 8) | ord ($drawing_text[$i]);
		}
		$glyphs        = $font->glyphNumbersForCharacters($characters);
		$widths        = $font->widthsForGlyphs($glyphs);
		$text_width   = (array_sum($widths) / $font->getUnitsPerEm()) * ($font_size);
		return $text_width;
	}
}
?>
