<?php

class HeydayWkHtmlToPdfFileOutput implements HeydayWkHtmlToPdfOutputter
{

	protected $path = false;

	public function __construct($path, $random = false, $force = false)
	{

		if ($random) {

			if (file_exists($path)) {

				$gen = new RandomGenerator;
				$this->path = realpath($path) . DIRECTORY_SEPARATOR . md5(time() . $gen->generateHash('sha1')) . '.pdf';

			} else if (is_writable($path)) {

				mkdir($path);

			} else {

				user_error('Directory doesn\'t exist and is not writable');

			}

		} else {

			if (file_exists($path) && !$force) {

				user_error('File already exists. If you want to overwrite the file use the $force option');

			} else if(!is_writable(dirname($path))) {

				user_error('Directory is not writable');

			} else {

				$this->path = $path;

			}

		}

	}

	public function process(WKPDF $wkpdf, HeydayWkHtmlToPdfInputter $inputter)
	{

		$wkpdf->set_html($inputter->process());
		$wkpdf->render();
		$wkpdf->output(WKPDF::$PDF_SAVEFILE, $this->path);

	}

}