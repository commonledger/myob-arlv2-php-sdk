<?php



namespace MYOB\AccountRight\Api;


class GeneralLedger extends AbstractEndpoint {

    private $prefix = 'GeneralLedger';

    public function taxCode(){
        return new GeneralLedger\TaxCode($this->prefix, $this->client);
    }

    public function account(){
        return new GeneralLedger\Account($this->prefix, $this->client);
    }

    public function accountingProperties(){
        return new GeneralLedger\AccountingProperties($this->prefix, $this->client);
    }

    public function generalJournal(){
        return new GeneralLedger\GeneralJournal($this->prefix, $this->client);
    }

    public function journalTransaction(){
        return new GeneralLedger\JournalTransaction($this->prefix, $this->client);
    }

}