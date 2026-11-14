<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\FocusArea;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use App\Services\PortfolioDataService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * One place for the smaller pieces of portfolio content: About focus cards,
 * highlight boxes, social links and every editable text / SEO field.
 */
class SiteContentController extends Controller
{
    public const SOCIAL_ICONS = ['github' => 'GitHub', 'linkedin' => 'LinkedIn', 'kaggle' => 'Kaggle', 'mail' => 'Email', 'x' => 'X / Twitter', 'facebook' => 'Facebook', 'youtube' => 'YouTube', 'instagram' => 'Instagram', 'whatsapp' => 'WhatsApp', 'link' => 'Website / other'];

    public function index(Request $request)
    {
        $tab = in_array($request->query('tab'), ['texts', 'about', 'highlights', 'socials'], true) ? $request->query('tab') : 'texts';
        $textGroups = config('site_texts');
        $texts = [];
        foreach ($textGroups as $fields) {
            foreach ($fields as $key => $def) {
                $texts[$key] = SiteSetting::get('text_' . $key, $def[1]);
            }
        }

        return view('admin.site-content.index', [
            'tab' => $tab,
            'textGroups' => $textGroups,
            'texts' => $texts,
            'focusAreas' => FocusArea::orderBy('order')->get(),
            'achievements' => Achievement::orderBy('order')->get(),
            'socials' => SocialLink::orderBy('order')->get(),
            'socialIcons' => self::SOCIAL_ICONS,
        ]);
    }

    public function updateTexts(Request $request)
    {
        $rules = [];
        foreach (config('site_texts') as $fields) {
            foreach ($fields as $key => $def) {
                $rules['texts.' . $key] = 'nullable|string|max:' . ($def[2] === 'textarea' ? 600 : 200);
            }
        }
        $validated = $request->validate($rules);

        foreach ($validated['texts'] ?? [] as $key => $value) {
            SiteSetting::set('text_' . $key, trim((string) $value));
        }
        PortfolioDataService::flush();

        return $this->back('texts', 'Texts and SEO saved.');
    }

    // ---- Focus areas (About cards)
    public function storeFocus(Request $request)
    {
        FocusArea::create($this->focusData($request) + ['order' => (int) FocusArea::max('order') + 1]);

        return $this->back('about', 'Focus card added.');
    }

    public function updateFocus(Request $request, FocusArea $focus)
    {
        $focus->update($this->focusData($request));

        return $this->back('about', 'Focus card updated.');
    }

    public function destroyFocus(FocusArea $focus)
    {
        $focus->delete();

        return $this->back('about', 'Focus card deleted.');
    }

    private function focusData(Request $request): array
    {
        $v = $request->validate([
            'title' => 'required|string|max:120',
            'description' => 'required|string|max:600',
            'number' => 'nullable|string|max:6',
            'order' => 'nullable|integer|min:0|max:999',
        ]);

        return array_filter([
            'title' => $v['title'],
            'description' => $v['description'],
            'number' => $v['number'] ?? str_pad((string) (FocusArea::count() + 1), 2, '0', STR_PAD_LEFT),
            'order' => $v['order'] ?? null,
        ], fn ($x) => $x !== null);
    }

    // ---- Highlights (achievements)
    public function storeHighlight(Request $request)
    {
        Achievement::create($this->highlightData($request) + ['order' => (int) Achievement::max('order') + 1]);

        return $this->back('highlights', 'Highlight added.');
    }

    public function updateHighlight(Request $request, Achievement $highlight)
    {
        $highlight->update($this->highlightData($request));

        return $this->back('highlights', 'Highlight updated.');
    }

    public function destroyHighlight(Achievement $highlight)
    {
        $highlight->delete();

        return $this->back('highlights', 'Highlight deleted.');
    }

    private function highlightData(Request $request): array
    {
        $v = $request->validate([
            'category' => 'required|string|max:60',
            'title' => 'required|string|max:120',
            'description' => 'nullable|string|max:300',
            'order' => 'nullable|integer|min:0|max:999',
        ]);

        return array_filter($v, fn ($x) => $x !== null);
    }

    // ---- Social links
    public function storeSocial(Request $request)
    {
        $data = $this->socialData($request);
        $data['platform'] = Str::slug($data['name']) . '-' . Str::lower(Str::random(4));
        SocialLink::create($data + ['order' => (int) SocialLink::max('order') + 1]);
        PortfolioDataService::flush();

        return $this->back('socials', 'Social link added.');
    }

    public function updateSocial(Request $request, SocialLink $social)
    {
        $social->update($this->socialData($request));

        return $this->back('socials', 'Social link updated.');
    }

    public function destroySocial(SocialLink $social)
    {
        $social->delete();

        return $this->back('socials', 'Social link deleted.');
    }

    private function socialData(Request $request): array
    {
        $v = $request->validate([
            'name' => 'required|string|max:40',
            'url' => ['required', 'string', 'max:255', 'regex:#^(https?://|mailto:|tel:)#i'],
            'handle' => 'nullable|string|max:100',
            'icon' => 'required|string|in:' . implode(',', array_keys(self::SOCIAL_ICONS)),
            'is_active' => 'nullable|boolean',
            'order' => 'nullable|integer|min:0|max:999',
        ], ['url.regex' => 'The link must start with https://, mailto: or tel:']);
        $v['is_active'] = $request->boolean('is_active', true);

        return array_filter($v, fn ($x) => $x !== null);
    }

    private function back(string $tab, string $msg)
    {
        return redirect()->route('admin.site-content', ['tab' => $tab])->with('status', $msg);
    }
}
