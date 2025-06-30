@props(['releases', 'repo'])

<div class="release-notes">
    <h2>Releases de {{ $repository }}</h2>
    <ul class="release-list" style="list-style: none; padding: 0;">
        @forelse($releases as $release)
            <li class="mb-4">
                <div class="card mb-3" style="box-shadow: 0 2px 8px #0001; border-radius: 8px; border: 1px solid #eee;">
                    <div class="card-body">
                        <strong class="card-title" style="font-size: 1.2em;">{{ $release['name'] ?? $release['tag_name'] }}</strong>
                        <div class="card-text" style="margin-bottom: 1em;">{!! nl2br(e($release['body'] ?? '')) !!}</div>
                        <ul style="padding-left: 1em;">
                            @foreach($release['assets'] as $asset)
                                <li>
                                    @php
                                        $isSource = preg_match('/archive\/refs\/tags\/v[\d.]+\.(zip|tar\.gz|tar|gz|bz2|7z|rar)$/i', $asset['browser_download_url'] ?? $asset['url']);
                                    @endphp
                                    @if(!$isSource)
                                        <a href="{{ URL::signedRoute('release-notes.download', [
                                            'repo' => $repository,
                                            'asset_url' => $asset['url'] ?? $asset['browser_download_url'],
                                            'file_name' => $asset['name']
                                        ]) }}" target="_blank">
                                            {{ $asset['name'] }}
                                        </a>
                                    @else
                                        <span class="text-muted">{{ $asset['name'] }} (descarga de código fuente no permitida)</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </li>
        @empty
            <li>No hay releases disponibles.</li>
        @endforelse
    </ul>
</div>
