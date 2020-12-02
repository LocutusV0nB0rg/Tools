<?php
class GithubRepositorySync {

    const GITHUB_API = "https://api.github.com/";
    const USER_AGENT = "GithubRepositorySync v1.1";

    protected $githubUsername;
    protected $githubRepository;
    protected $githubAccessToken;

    public function __construct(String $githubUsername, String $githubRepository, String $githubAccessToken = "") {
        $this->githubUsername = $githubUsername;
        $this->githubRepository = $githubRepository;
        $this->githubAccessToken = $githubAccessToken;
    }

    public function downloadBranch(String $branch, String $downloadTo, bool $cleanDestination = true) {
        if($cleanDestination) {
            $glob = glob($downloadTo."/*");
            foreach($glob as $file) {
                if(is_dir($file)) {
                    static::deleteFolder($file);
                } else {
                    unlink($file);
                }
            }
        }

        $tmpFile = tmpfile();
        $tmpFilePath = stream_get_meta_data($tmpFile)['uri'];

        $giturl = static::GITHUB_API."repos/".$this->githubUsername."/".$this->githubRepository."/zipball/".$branch;
        $ch = curl_init($giturl);
        curl_setopt($ch, CURLOPT_FILE, $tmpFile);
        curl_setopt($ch, CURLOPT_USERAGENT, static::USER_AGENT);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        if(!empty($this->githubAccessToken)) {
            curl_setopt($ch, CURLOPT_HEADER, 'Authorization: token '.$this->githubAccessToken);
        }

        $output = curl_exec($ch);

        if(curl_errno($ch)){
            throw new \Exception(curl_error($ch));
        }

        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if($statusCode == 200) {
            $zip = new \ZipArchive();
            if ($zip->open($tmpFilePath) === true) {
                $zip->extractTo($downloadTo);
                $zip->close();
                
                fclose($tmpFile);
            } else {
                throw new \Exception("could not open the tmp file");
            }
        } else {
            throw new \Exception("got invalid response code from github: ".$statusCode);

        }
    }

    private static function deleteFolder(String $dirname) {
        if(is_dir($dirname)) {
            $directoryHandle = opendir($dirname);
        }
        if (!$directoryHandle) {
            return false;
        }

        while($file = readdir($directoryHandle)) {
            if($file != "." && $file != "..") {
                if(!is_dir($dirname."/".$file)) {
                    unlink($dirname."/".$file);
                } else {
                    static::deleteFolder($dirname.'/'.$file);
                }
            }
        }

        closedir($directoryHandle);
        rmdir($dirname);
        return true;
    }
}