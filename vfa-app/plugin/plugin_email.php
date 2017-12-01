<?php
/*
 * This file is part of Mkframework. Mkframework is free software: you can redistribute it and/or modify it under the terms of the GNU Lesser General Public License as published by the Free Software Foundation, either version 3 of the License. Mkframework is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser General Public License for more details. You should have received a copy of the GNU Lesser General Public License along with Mkframework. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * plugin_auth classe pour gerer l'authentification
 *
 * @author Mika
 * @link http://mkf.mkdevs.com/
 *
 */
class plugin_email
{
    const CONTENT_TYPE_TEXT = 0;
    const CONTENT_TYPE_HTML = 1;
    const CONTENT_TYPE_MULTI_ALT = 2;

    private $sFrom;

    private $sFromLibelle;

    private $tTo;

    private $tCC;

    private $tBCC;

    private $sTitle;

    private $sBody;

    private $sBodyHtml;

    private $sAttached;

    private $sErrors;

    private $sWall;

    public function __construct()
    {
        $this->sWall = "=" . md5(rand());
    }

    public function setFrom($sFromLibelle, $sFrom)
    {
        $this->sFrom = $sFrom;
        $this->sFromLibelle = $sFromLibelle;
    }

    public function addTo($sTo)
    {
        $this->tTo[$sTo] = $sTo;
    }

    public function addCC($sCC)
    {
        $this->tCC[$sCC] = $sCC;
    }

    public function addBCC($sBCC)
    {
        $this->tBCC[$sBCC] = $sBCC;
    }

    public function setTitle($sTitle)
    {
        $this->sTitle = $sTitle;
    }

    public function setSubject($sTitle)
    {
        $this->sTitle = $sTitle;
    }

    public function setBody($sBody)
    {
        $this->sBody = $sBody;
    }

    public function setBodyHtml($sBody)
    {
        $this->sBodyHtml = $sBody;
    }

    public function attachFile($sFile)
    {
        $this->sAttached = $sFile;
    }

    private function checkEmail($sEmail)
    {
        return preg_match('/^([a-zA-Z0-9\-\_\.]*)@([a-zA-Z0-9\-\_\.]*)\.([a-zA-Z]*)$/', $sEmail);
    }

    private function isValid()
    {
        $ok = 1;

        $tErrors = array();
        if ($this->sFrom == '') {
            $ok = 0;
            $tErrors[] = "Pas d'email from";
        } elseif (!$this->checkEmail($this->sFrom)) {
            $ok = 0;
            $tErrors[] = "Mauvais format pour l'email from (" . $this->sFrom . ")";
        }

        if (count($this->tTo) == 0) {
            $ok = 0;
            $tErrors[] = "Pas d'email to";
        } else {
            foreach ($this->tTo as $sTo) {
                if (!$this->checkEmail($sTo)) {
                    $ok = 0;
                    $tErrors[] = "Mauvais format pour l'email to (" . $sTo . ")";
                }
            }
        }

        if ($this->sTitle == '') {
            $ok = 0;
            $tErrors[] = "Pas de sujet";
        }

        if (!$ok) {
            $this->sErrors = "Erreur plugin_email lors de l envoi de l'email \n";
            $this->sErrors .= implode("\n", $tErrors);
        }

        return $ok;
    }

    private function buildBody($pNextLine, $pBoundary = true)
    {
        $sMsg = '';
        if ($this->sBody != '') {
//			$sMsg .= $pNextLine;
            if ($pBoundary) {
                $sMsg .= '--' . $this->sWall . $pNextLine;
                // $sMsg .= 'Content-Type: text/plain; charset="iso-8859-1"' . $pNextLine;
                $sMsg .= 'Content-Type: text/plain; charset="utf-8"' . $pNextLine;
                $sMsg .= "Content-Transfer-Encoding: 8bit" . $pNextLine;
                $sMsg .= $pNextLine;
            }
            $sMsg .= $this->sBody;
        }
        return $sMsg;
    }

    private function buildBodyHtml($pNextLine, $pBoundary = true)
    {
        $sMsg = '';
        if ($this->sBodyHtml != '') {
//			$sMsg .= $pNextLine;
            if ($pBoundary) {
                $sMsg .= '--' . $this->sWall . $pNextLine;
                // $sMsg .= 'Content-Type: text/html; charset="iso-8859-1"' . $pNextLine;
                $sMsg .= 'Content-Type: text/html; charset="utf-8"' . $pNextLine;
                $sMsg .= "Content-Transfer-Encoding: 8bit" . $pNextLine;
                //$sMsg .= $pNextLine;
            }
            $sMsg .= $this->sBodyHtml;
        }
        return $sMsg;
    }

    private function buildAttached($pNextLine, $pBoundary = true)
    {
        $sMsg = '';
        if ($this->sAttached != '') {
            $sMsg .= $pNextLine;
            if ($pBoundary) {
                $sMsg .= '--' . $this->sWall . $pNextLine;
            }
            $sMsg .= 'Content-Type: text/csv; name="' . $this->sAttached . '"' . $pNextLine;
            $sMsg .= 'Content-Transfer-Encoding: base64' . $pNextLine;
            $sMsg .= 'Content-Disposition:attachement; filename="' . $this->sAttached . '"' . $pNextLine;
            $sMsg .= chunk_split(base64_encode(file_get_contents($this->sAttached))) . $pNextLine;
        }
        return $sMsg;
    }

    private function findConstContentType()
    {
        if (($this->sBody != '') && ($this->sBodyHtml != '')) {
            return self::CONTENT_TYPE_MULTI_ALT;
        }
        if ($this->sBody != '') {
            return self::CONTENT_TYPE_TEXT;
        }
        return self::CONTENT_TYPE_HTML;
    }

    public function build()
    {
        $mail = array();

        $n = "\n";
        $r = "\r";
        $rn = $r . $n;

        $sHeader = 'From: "' . $this->sFromLibelle . '" <' . $this->sFrom . '>' . $n;
        if ($this->tBCC) {
            $sHeader .= 'Bcc: ' . implode(',', $this->tBCC) . $n;
        }
        if ($this->tCC) {
            $sHeader .= 'Cc: ' . implode(',', $this->tCC) . $n;
        }
        $sHeader .= 'Reply-To: ' . $this->sFrom . $n;
        $sHeader .= 'MIME-Version: 1.0' . $n;

        $sMsg = '';
        switch ($this->findConstContentType()) {
            case self::CONTENT_TYPE_TEXT:
                $sHeader .= 'Content-Type: text/plain; charset="utf-8"' . $n;
                $sMsg .= $this->buildBody($rn, false);
                break;
            case self::CONTENT_TYPE_HTML:
                $sHeader .= 'Content-Type: text/html; charset="utf-8"' . $n;
                $sMsg .= $this->buildBodyHtml($rn, false);
                break;
            default:
                $sHeader .= 'Content-Type: multipart/alternative; boundary="' . $this->sWall . '"';
                $sMsg .= $this->buildBody($n);
                $sMsg .= $n;
                $sMsg .= $this->buildBodyHtml($n);
                $sMsg .= $n . '--' . $this->sWall . '--';
                break;
        }

        $mail['to'] = implode(',', $this->tTo);
        $mail['object'] = '=?utf-8?B?' . base64_encode($this->sTitle) . '?=';
        $mail['message'] = $sMsg;
        $mail['header'] = $sHeader;
//		printf('<br/>header<br/>');
//		var_dump($sHeader);
//
//		printf('<br/>body<br/>');
//		var_dump($sMsg);
//		printf('<br/>tableau<br/>');
//		var_dump($mail);
        return $mail;
    }

    public function send()
    {
        if (!$this->isValid()) {
            throw new Exception($this->sErrors);
        }

        $mail = $this->build();

        if (mail($mail['to'], $mail['object'], $mail['message'], $mail['header'])) {
            return true;
        } else {
            return false;
        }
    }
}
