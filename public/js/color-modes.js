/*!
 * Color mode toggler for Bootstrap's docs (https://getbootstrap.com/)
 * Copyright 2011-2023 The Bootstrap Authors
 * Licensed under the Creative Commons Attribution 3.0 Unported License.
 */

(() => {
  'use strict'

  const getStoredTheme = () => localStorage.getItem('theme')
  const setStoredTheme = theme => localStorage.setItem('theme', theme)

  const getPreferredTheme = () => {
    return 'dark';
  }

  const setTheme = theme => {
    if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
      document.documentElement.setAttribute('data-bs-theme', 'dark')
    } else {
      document.documentElement.setAttribute('data-bs-theme', theme)
    }
  }

  setTheme(getPreferredTheme())

  const showActiveTheme = (theme, focus = false) => {
    const themeSwitcher = document.querySelector('#bd-theme')

    if (!themeSwitcher) {
      return
    }

    const themeSwitcherText = document.querySelector('#bd-theme-text')
    const activeThemeIcon = document.querySelector('.theme-icon-active use')
    const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`)
    const svgOfActiveBtn = btnToActive.querySelector('svg use').getAttribute('href')

    document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
      element.classList.remove('active')
      element.setAttribute('aria-pressed', 'false')
    })

    btnToActive.classList.add('active')
    btnToActive.setAttribute('aria-pressed', 'true')
    activeThemeIcon.setAttribute('href', svgOfActiveBtn)
    const themeSwitcherLabel = `${themeSwitcherText.textContent} (${btnToActive.dataset.bsThemeValue})`
    themeSwitcher.setAttribute('aria-label', themeSwitcherLabel)

    if (focus) {
      themeSwitcher.focus()
    }
  }

  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
    const storedTheme = getStoredTheme()
    if (storedTheme !== 'light' && storedTheme !== 'dark') {
      setTheme(getPreferredTheme())
    }
  })
  const updateOutlinedBtn = theme => {
    document.querySelectorAll('.dark-mode-btn').forEach(btn => {
      if (theme === 'dark') {
        btn.classList.remove('btn-outline-dark');
        btn.classList.add('btn-outline-light');
      } else {
        btn.classList.remove('btn-outline-light');
        btn.classList.add('btn-outline-dark');
      }
    });
  };




  const updateLogo = theme => {
    const logoElement = document.querySelector('.theme-logo');
  
    // Chemin relatif par rapport au fichier HTML qui charge le script
    let imagePath;
    // let isParentPage = window.location.pathname === '/index.html' || window.location.pathname === '/';
    // if (isParentPage) {
    //   imagePath = '../../../public/img/';
    // } else {
      imagePath = '/img/';
    //}
    if (theme === 'dark') {
      logoElement.src = imagePath + 'logoFN.png';
      logoElement.alt = 'Dark Logo';
    } else {
      logoElement.src = imagePath + 'logoFB.png';
      logoElement.alt = 'Light Logo';
    }
  };  
  
  window.addEventListener('DOMContentLoaded', () => {
    showActiveTheme(getPreferredTheme())
    

    document.querySelectorAll('[data-bs-theme-value]')
      .forEach(toggle => {
        toggle.addEventListener('click', () => {
          const theme = toggle.getAttribute('data-bs-theme-value')
          setStoredTheme(theme)
          setTheme(theme)
          showActiveTheme(theme, true)
          updateLogo(theme) 
          updateOutlinedBtn(theme)
        })
      })
  })
})()
