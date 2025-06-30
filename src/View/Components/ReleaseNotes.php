<?php

namespace Sideso\ReleaseNotes\View\Components;

use Illuminate\View\Component;
use Sideso\ReleaseNotes\Services\GithubReleaseService;

class ReleaseNotes extends Component
{
    public $repository;
    public $releases;

    public function __construct(string $repository, array $allowedAssets, GithubReleaseService $service)
    {
        $this->repository = $repository;
        //use GithubReleaseService to fetch releases
        $this->releases =$service->getReleases(
            $repository,
            config('release-notes.allowed_assets', $allowedAssets),
            config('release-notes.per_page', 10)
        );
    }

    public function render()
    {
        return view('release-notes::components.release-notes');
    }
}
