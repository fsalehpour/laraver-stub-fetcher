<?php
namespace LaravelStubs;

use Illuminate\Http\Response;

class StubFetcher
{
    /**
     * @var array
     */
    private $stubs;
    /**
     * @var string
     */
    private $path;

    /**
     * StubProvider constructor.
     * @param array $stubs
     * @param string $pathFromBase
     */
    public function __construct(array $stubs, string $pathFromBase)
    {
        $this->stubs = $stubs;
        $this->path = $pathFromBase;
    }

    public function fetchFor(string $json): Response
    {
        $request = json_decode($json);
        foreach ($this->stubs as $file => $stub) {
            $stubReq = json_decode($this->getStubContents($file));
            if ($stubReq == $request)
                return $this->getResponse($stub);
        }
        return new Response(json_encode(['errors' => [['title' => 'stub not found']]]), 404, ['Content-Type' => 'application/json']);
    }

    /**
     * @param $file
     * @return false|string
     */
    protected function getStubContents($file)
    {
        return file_get_contents($this->path . "/" . $file);
    }

    /**
     * @param $stub
     * @return Response
     */
    protected function getResponse($stub): Response
    {
        return new Response(
            $this->getStubContents($stub['file']),
            $stub['status'],
            ['Content-Type' => 'application/json']
        );
    }
}