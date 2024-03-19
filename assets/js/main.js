$(document).ready(() => {
  /*  Show/Hidden Submenus */
  $('.nav-btn-submenu').on('click', function (e) {
    e.preventDefault()
    const subMenu = $(this).next('ul')
    const iconBtn = $(this).children('.fa-chevron-down')
    if (subMenu.hasClass('show-nav-lateral-submenu')) {
      $(this).removeClass('active')
      iconBtn.removeClass('fa-rotate-180')
      subMenu.removeClass('show-nav-lateral-submenu')
    } else {
      $(this).addClass('active')
      iconBtn.addClass('fa-rotate-180')
      subMenu.addClass('show-nav-lateral-submenu')
    }
  })

  /*  Show/Hidden Nav Lateral */
  $('.show-nav-lateral').on('click', e => {
    e.preventDefault()
    const navLateral = $('.nav-lateral')
    const pageConten = $('.page-content')
    if (navLateral.hasClass('active')) {
      navLateral.removeClass('active')
      pageConten.removeClass('active')
    } else {
      navLateral.addClass('active')
      pageConten.addClass('active')
    }
  })
})
;($ => {
  $(window).on('load', () => {
    $('.nav-lateral-content').mCustomScrollbar({
      theme: 'light-thin',
      scrollbarPosition: 'inside',
      autoHideScrollbar: true,
      scrollButtons: { enable: true }
    })
    $('.page-content').mCustomScrollbar({
      theme: 'dark-thin',
      scrollbarPosition: 'inside',
      autoHideScrollbar: true,
      scrollButtons: { enable: true }
    })
  })
})(jQuery)

$(() => {
  $('[data-toggle="popover"]').popover()
})
