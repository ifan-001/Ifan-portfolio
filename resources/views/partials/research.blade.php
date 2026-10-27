<section class="section" id="research">
    <div class="container">
        <div class="section-header">
            <span class="section-eyebrow">Academic Exploration</span>
            <div style="display:flex; align-items:center; justify-content:center; gap:12px; flex-wrap:wrap;">
                <h2 class="section-title" style="margin-bottom:0;">Beyond Application Development</h2>
                @auth
                    <button type="button" class="admin-edit-trigger" onclick="openAddResearchModal()" title="Add research exploration or achievement highlight">
                        + Add Research / Highlight
                    </button>
                @endauth
            </div>
            <p class="section-desc" style="margin-top:16px;">
                {{ $portfolio['research']['intro'] }}
            </p>
        </div>

        <div class="research-grid">
            @foreach($portfolio['research']['interests'] as $item)
                <div class="research-card">
                    <div style="display:flex; align-items:center; justify-content:space-between;">
                        <div class="research-icon">
                            @if($item['icon'] === 'cpu')
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect>
                                    <rect x="9" y="9" width="6" height="6"></rect>
                                    <line x1="9" y1="1" x2="9" y2="4"></line>
                                    <line x1="15" y1="1" x2="15" y2="4"></line>
                                    <line x1="9" y1="20" x2="9" y2="23"></line>
                                    <line x1="15" y1="20" x2="15" y2="23"></line>
                                    <line x1="20" y1="9" x2="23" y2="9"></line>
                                    <line x1="20" y1="14" x2="23" y2="14"></line>
                                    <line x1="1" y1="9" x2="4" y2="9"></line>
                                    <line x1="1" y1="14" x2="4" y2="14"></line>
                                </svg>
                            @elseif($item['icon'] === 'scan')
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M3 7V5a2 2 0 0 1 2-2h2"></path>
                                    <path d="M17 3h2a2 2 0 0 1 2 2v2"></path>
                                    <path d="M21 17v2a2 2 0 0 1-2 2h-2"></path>
                                    <path d="M7 21H5a2 2 0 0 1-2-2v-2"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            @else
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                    <polyline points="9 12 11 14 15 10"></polyline>
                                </svg>
                            @endif
                        </div>
                        @auth
                            @if(!empty($item['id']))
                                <button type="button" class="admin-btn-mini danger" onclick="deleteResearchItem({{ $item['id'] }}, '{{ addslashes($item['title']) }}')" title="Delete topic">
                                    ✖ Delete
                                </button>
                            @endif
                        @endauth
                    </div>
                    <h3 class="research-title">{{ $item['title'] }}</h3>
                    <p class="research-desc">{{ $item['description'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
