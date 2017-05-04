<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class SightSeek extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'sight:seek';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $url = "http://www.mafengwo.cn/jd/10143/gonglve.html";
        var_dump(function_exists("simple_html_dom"));
         
        
        $content = file_get_contents($url);
        
        preg_match_all('/<div class="crumb">(.*?)<\/script>/is',$content,$matches);
        $brmd = $matches[1][0];
        
        preg_match_all('/<span class="hd">(.*?)<\/span>/is',$brmd,$matches);
        $regions = array();
        foreach($matches[1] as $html) {
            echo $html,PHP_EOL;
            $html = preg_replace('/<\/?[a-z].*?>/is','',$html);
            echo $html,PHP_EOL;
            $regions[] = $html;
        }
        $province = $regions[0];
        $city = $regions[1];
        $region = $regions[2];
        print_r($regions);
		//
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['CountryCode', InputArgument::REQUIRED, 'country of the sight.'],
			['ProvinceId', InputArgument::REQUIRED, 'province of the sight.'],
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
			//['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
		];
	}

}
