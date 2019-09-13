<?php

class MarkovNameGenerator {
	private $m_order;
    private $m_minLength;
	private $m_samples = array();
	private $m_chains = array();
	private $m_used = array();
	
	public function __construct($sampleNames, $order, $minLength) {
        if ($order < 1)
            $order = 1;
        if ($minLength < 1)
            $minLength = 1;
        $this->m_order = $order;
        $this->m_minLength = $minLength;
        foreach ($sampleNames as $line)
        {
            $tokens = mb_split(',', $line);
            foreach ($tokens as $word)
            {
                $upper = mb_strtoupper(trim($word));
                if (mb_strlen($upper) < $order + 1)
                    continue;
				array_push($this->m_samples, $upper);
            }
        }
        foreach ($this->m_samples as $word)
        {
            for ($letter = 0; $letter < mb_strlen($word) - $order; $letter++)
            {
                $token = mb_substr($word, $letter, $order);
                if (array_key_exists($token, $this->m_chains)) {	
                    array_push($this->m_chains[$token], mb_substr($word, $letter + $order, 1));
				}
                else
                {
                    $entry = array();
					array_push($entry, mb_substr($word, $letter + $order, 1));
                    $this->m_chains[$token] = $entry;
                }
            }
        }
	}

	public function GetNextName()
    {
        $s;
        do
        {
            $n = rand(0, count($this->m_samples) - 1);
            $nameLength = mb_strlen($this->m_samples[$n]);
			$s = mb_substr($this->m_samples[$n], rand(0, ($nameLength - $this->m_order) - 1), $this->m_order);
            while (mb_strlen($s) < $nameLength)
            {
				$token = mb_substr($s, mb_strlen($s) - $this->m_order, $this->m_order);
                $c = $this->GetLetter($token);
                if ($c != '?')
                    $s .= $this->GetLetter($token);
                else
                    break;
            }
            if (strpos($s, ' ') != false)
            {
                $tokens = mb_split(' ', $s);
                $s = '';
                for ($t = 0; $t < count($tokens); $t++)
                {
                    if ($tokens[$t] == "")
                        continue;
                    if (mb_strlen($tokens[$t]) == 1)
                        $tokens[$t] = mb_strtoupper($tokens[$t]);
                    else
                        $tokens[$t] = mb_substr($tokens[$t], 0, 1) . mb_strtolower(mb_substr($tokens[$t], 1));
                    if ($s != '')
                        $s .= ' ';
                    $s .= $tokens[$t];
                }
            }
            else
                $s = mb_substr($s, 0, 1) . mb_strtolower(mb_substr($s, 1));
        }
        while (in_array($s, $this->m_used) || mb_strlen($s) < $this->m_minLength);
		array_push($this->m_used, $s);
        return $s;
    }
	
	public function GetLetter ($token)
    {
        if (!array_key_exists($token, $this->m_chains))
            return '?';
        $letters = $this->m_chains[$token];
        $n = rand(0, count($letters) - 1);
        return $letters[$n];
    }
}