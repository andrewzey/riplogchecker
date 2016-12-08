<?php

namespace RipLogChecker\Parsers;

class EacParser extends BaseParser
{
    /**
     * Creates a new EacParser object based on the log file provided, and parses it
     *
     * RipLogChecker constructor.
     * @param string $log
     */
    public function __construct(string $log)
    {
        /* Load the log file */
        $this->log = $log;
        $this->errors = [];
        /* Initialize deducted points */
        $this->setDeductedPoints(0);
    }

    /**
     * Parses the log file, and returns false if it
     * fails to parse the log file
     *
     * @return bool
     */
    public function parse(): bool
    {
        /* If the log is empty, return false */
        if (!$this->log) {
            return false;
        }

        /* Parse $this->log, and return false if even one check fails
         * TODO: refactor this into something nicer if possible
         */
        if (!$this->checkReadMode()) return false;
        if (!$this->checkDefeatAudioCache()) return false;
        if (!$this->checkC2PointersUsed()) return false;
        if (!$this->checkFillUpOffsetSamples()) return false;
        if (!$this->checkSilentBlockDeletion()) return false;
        if (!$this->checkNullSamplesUsed()) return false;
        if (!$this->checkGapHandling()) return false;
        if (!$this->checkID3TagsAdded()) return false;
        if (!$this->checkCRCMismatch()) return false;
        if (!$this->checkTestCopyUsed()) return false;

        /* Log parsed successfully, return true */
        return true;
    }

    /**
     * Check whether the read mode is secure or insecure,
     * set the $deductedPoints and return false if it fails
     * to check the read mode
     *
     * @return bool
     */
    protected function checkReadMode(): bool
    {
        $pattern = "/Read mode               : Secure/";
        $result = preg_match($pattern, $this->log, $matches);

        return $this->processResult($result, self::INSECURE_MODE_USED);
    }

    /**
     * Check if "Defeat audio cache" is set to yes,
     * set the $deductedPoints and return false if it fails
     * to check the read mode
     *
     * @return bool
     */
    protected function checkDefeatAudioCache(): bool
    {
        $pattern = "/Defeat audio cache      : Yes/";
        $result = preg_match($pattern, $this->log, $matches);

        return $this->processResult($result, self::DEFEAT_AUDIO_CACHE_DISABLED);
    }

    /**
     * Check if C2 pointers is set to no,
     * set the $deductedPoints and return false if it fails
     * to check the read mode
     *
     * @return bool
     */
    protected function checkC2PointersUsed(): bool
    {
        // TODO: Implement checkC2Pointers() method.
        return true;
    }

    /**
     * Check whether "Fill up missing offset samples with silence"
     * is set to yes and set the $deductedPoints and return false
     * if it fails
     *
     * @return bool
     */
    protected function checkFillUpOffsetSamples(): bool
    {
        // TODO: Implement checkFillUpOffsetSamples() method.
        return true;
    }

    /**
     * Check if "Delete leading and trailing silent blocks"
     * is set to "No" and set the $deductedPoints and return
     * false if it fails
     *
     * @return bool
     */
    protected function checkSilentBlockDeletion(): bool
    {
        // TODO: Implement checkSilentBlockDeletion() method.
        return true;
    }

    /**
     * Check if Null samples are used in CRC calculations,
     * set the $deductedPoints and return false if it fails
     *
     * @return bool
     */
    protected function checkNullSamplesUsed(): bool
    {
        // TODO: Implement checkNullSamplesUsed() method.
        return true;
    }

    /**
     * Check whether gap handling was detected
     * and if the correct mode was used, then set
     * $deductedPoints. Returns false on failure;
     *
     * @return bool
     */
    protected function checkGapHandling(): bool
    {
        // TODO: Implement checkGapHandling() method.
        return true;
    }

    /**
     * Check whether ID3 tags were added. Set
     * $deductedPoints and return false on failure.
     *
     * @return bool
     */
    protected function checkID3TagsAdded(): bool
    {
        // TODO: Implement checkID3TagsAdded() method.
        return true;
    }

    /**
     * Check if there are CRC mismatches in the log.
     * Set $deductedPoints and return false on failure.
     *
     * @return bool
     */
    protected function checkCRCMismatch(): bool
    {
        // TODO: Implement checkCRCMismatch() method.
        return true;
    }

    /**
     * Check if Test & Copy is used, and deduct points if not.
     * Return false on failure.
     *
     * @return bool
     */
    protected function checkTestCopyUsed(): bool
    {
        // TODO: Implement checkTestCopyUsed() method.
        return true;
    }

    /**
     * Processes the result of a preg_match()
     *
     * @param $result - The preg_match() result
     * @param $check - The point deduction check constant
     * @return bool
     */
    protected function processResult($result, $check): bool
    {
        /* If we found a match, return true */
        if ($result === 1) {
            /* Set INSECURE_MODE_USED to false in $this->errors */
            $this->errors[$check] = false;

            /* Return true */
            return true;

        } /* If we haven't found a match, deduct score and return true */
        elseif ($result === 0) {
            /* Add -2 points to $this->deductedPoints */
            $this->deductedPoints += parent::$pointDeductions[$check];

            /* Set INSECURE_MODE_USED to true in $this->errors */
            $this->errors[$check] = true;
            return true;
        }

        /* Return false if preg_match fails */
        return $result;
    }
}