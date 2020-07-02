<?php


namespace com\bj58\zhuanzhuan\zzscf\protocol;


class Request
{
    private ?string  $lookup;
    private string $methodName;
    private ?array $paraKVList;
    private ?string $interfaceName;
    private ?array  $attachments = null;

    /**
     * @return string|null
     */
    public function getLookup(): ?string
    {
        return $this->lookup;
    }

    /**
     * @param string|null $lookup
     */
    public function setLookup(?string $lookup): void
    {
        $this->lookup = $lookup;
    }

    /**
     * @return string
     */
    public function getMethodName(): string
    {
        return $this->methodName;
    }

    /**
     * @param string $methodName
     */
    public function setMethodName(string $methodName): void
    {
        $this->methodName = $methodName;
    }

    /**
     * @return array|null
     */
    public function getParaKVList(): ?array
    {
        return $this->paraKVList;
    }

    /**
     * @param array|null $paraKVList
     */
    public function setParaKVList(?array $paraKVList): void
    {
        $this->paraKVList = $paraKVList;
    }

    /**
     * @return string|null
     */
    public function getInterfaceName(): ?string
    {
        return $this->interfaceName;
    }

    /**
     * @param string|null $interfaceName
     */
    public function setInterfaceName(?string $interfaceName): void
    {
        $this->interfaceName = $interfaceName;
    }

    /**
     * @return array|null
     */
    public function getAttachments(): ?array
    {
        return $this->attachments;
    }

    /**
     * @param array|null $attachments
     */
    public function setAttachments(?array $attachments): void
    {
        $this->attachments = $attachments;
    }




}