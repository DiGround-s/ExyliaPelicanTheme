{{--
    Exylia galactic atmosphere layer.
    Injected at PanelsRenderHook::BODY_START. Purely decorative, pointer-events
    disabled, aria-hidden. Visibility is driven by the body classes set in the
    plugin head render hook (.exylia-glow / .exylia-starfield) and by CSS so it
    costs nothing when the admin disables the effects.
--}}
<div class="exylia-atmosphere" aria-hidden="true">
    <div class="exylia-atmosphere__glow exylia-atmosphere__glow--one"></div>
    <div class="exylia-atmosphere__glow exylia-atmosphere__glow--two"></div>
    <div class="exylia-atmosphere__glow exylia-atmosphere__glow--three"></div>
    <div class="exylia-atmosphere__stars"></div>
    <div class="exylia-atmosphere__grid"></div>
</div>
