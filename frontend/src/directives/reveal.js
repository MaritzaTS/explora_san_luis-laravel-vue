const transforms = {
  up:    'translateY(36px)',
  down:  'translateY(-36px)',
  left:  'translateX(36px)',
  right: 'translateX(-36px)',
}

export default {
  mounted(el, binding) {
    const delay     = typeof binding.value === 'number' ? binding.value : 0
    const direction = binding.arg ?? 'up'

    Object.assign(el.style, {
      opacity:    '0',
      transform:  transforms[direction] ?? transforms.up,
      transition: `opacity 0.6s ease ${delay}ms, transform 0.6s ease ${delay}ms`,
      willChange: 'opacity, transform',
    })

    const observer = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting) {
          el.style.opacity   = '1'
          el.style.transform = 'none'
          observer.disconnect()
        }
      },
      { threshold: 0.1 }
    )

    observer.observe(el)
  },
}
