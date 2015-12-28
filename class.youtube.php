<?php

class youtube {
	public $videoInformation;

	public function __construct($id) {
		$this->ytID = $id;
	}

	public function youtube_id_from_url($url) {
		$pattern =
			'%^# Match any youtube URL
        (?:https?://)?  # Optional scheme. Either http or https
        (?:www\.)?      # Optional www subdomain
        (?:             # Group host alternatives
          youtu\.be/    # Either youtu.be,
        | youtube\.com  # or youtube.com
          (?:           # Group path alternatives
            /embed/     # Either /embed/
          | /v/         # or /v/
          | /watch\?v=  # or /watch\?v=
          )             # End path alternatives.
        )               # End host alternatives.
        ([\w-]{10,12})  # Allow 10-12 for 11 char youtube id.
        $%x'
		;
		$result = preg_match($pattern, $url, $matches);
		if (false !== $result) {
			return $matches[1];
		}
		return 'id not valid';
	}

	public function getID() {
		// A YouTube ID is 11 chars, so if only the ID was given, it doesn't need to extract it anymore, else extract it from the URL
		if (strlen($this->ytID) == 11) {}else{
			$this->ytID = $this->youtube_id_from_url($this->ytID);
		}
		return $this->ytID;
	}

	public function validateID() {
		$videoURL = 'http://youtu.be/'.$this->getID();
		$this->videoInformation = json_decode(file_get_contents(sprintf('http://www.youtube.com/oembed?url=%s&format=json', urlencode($videoURL))));
		if ($this->videoInformation == null) {
			return false;
		} else {
			// Also assign the video id to the information
			$this->videoInformation->id = $this->ytID;
			return true;
		}
	}

	public function videoInfo() {
		return $this->videoInformation;
	}
}