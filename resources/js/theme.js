/*
 * Exylia Theme — optional runtime enhancements.
 *
 * Kept intentionally tiny. Core visuals are pure CSS so the theme works even
 * if this bundle fails to load. Here we only add a parallax nudge to the
 * atmosphere glow on pointer move, disabled when motion is off.
 */
const motionOk =
    !document.documentElement.classList.contains('exylia-no-motion') &&
    !window.matchMedia('(prefers-reduced-motion: reduce)').matches

if (motionOk) {
    let raf = 0
    window.addEventListener('pointermove', (e) => {
        if (raf) return
        raf = requestAnimationFrame(() => {
            raf = 0
            const x = (e.clientX / window.innerWidth - 0.5) * 12
            const y = (e.clientY / window.innerHeight - 0.5) * 12
            const layer = document.querySelector('.exylia-atmosphere')
            if (layer) layer.style.transform = `translate3d(${x}px, ${y}px, 0)`
        })
    })
}
