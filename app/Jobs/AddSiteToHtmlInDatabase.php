<?php

namespace App\Jobs;

use App\Models\Company;
use App\Models\SiteHtml;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddSiteToHtmlInDatabase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private $company = null;

    /**
     * AddSiteToHtmlInDatabase constructor.
     * @param Company $company
     */
    public function __construct($company)
    {
        $this->company = $company;
    }

    public function handle()
    {
        try{
            $html = file_get_contents($this->company->getInternetAddress());

            $siteHtml = new SiteHtml();
            $siteHtml->setHtml(base64_encode($html));
            $siteHtml->setCompanyId($this->company->getId());

            $siteHtml->save();

        }catch (\Throwable $t){
            syslog(LOG_INFO, $t->getMessage());
        }

    }
}
