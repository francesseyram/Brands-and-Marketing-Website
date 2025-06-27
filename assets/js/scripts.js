/**
 * CREATIVE SUMMIT 2024 - MAIN JAVASCRIPT
 * Event Registration System Scripts
 */

// ===== GLOBAL VARIABLES =====
let mobileMenuOpen = false

// ===== DOM READY =====
document.addEventListener("DOMContentLoaded", () => {
  initializeApp()
})

// ===== MAIN INITIALIZATION =====
function initializeApp() {
  initializeFormValidation()
  initializeInteractiveElements()
  initializePasswordFeatures()
  initializeSearchAndSort()
  initializeAnimations()

  console.log("Creative Summit 2024 - App Initialized")
}

// ===== MOBILE MENU =====
function toggleMobileMenu() {
  const navLinks = document.querySelector(".nav-links")
  const toggleButton = document.querySelector(".mobile-menu-toggle")

  if (!navLinks || !toggleButton) return

  mobileMenuOpen = !mobileMenuOpen

  if (mobileMenuOpen) {
    navLinks.style.display = "flex"
    navLinks.style.flexDirection = "column"
    navLinks.style.position = "absolute"
    navLinks.style.top = "100%"
    navLinks.style.left = "0"
    navLinks.style.right = "0"
    navLinks.style.background = "rgba(15, 118, 110, 0.95)"
    navLinks.style.padding = "1rem"
    navLinks.style.borderRadius = "0 0 12px 12px"
    toggleButton.classList.add("active")
  } else {
    navLinks.style.display = ""
    navLinks.style.flexDirection = ""
    navLinks.style.position = ""
    navLinks.style.top = ""
    navLinks.style.left = ""
    navLinks.style.right = ""
    navLinks.style.background = ""
    navLinks.style.padding = ""
    navLinks.style.borderRadius = ""
    toggleButton.classList.remove("active")
  }
}

// ===== FORM VALIDATION =====
function initializeFormValidation() {
  const forms = document.querySelectorAll("form")

  forms.forEach((form) => {
    const inputs = form.querySelectorAll("input, select, textarea")

    inputs.forEach((input) => {
      input.addEventListener("blur", validateField)
      input.addEventListener("input", clearFieldError)
    })

    form.addEventListener("submit", handleFormSubmit)
  })
}

function validateField(event) {
  const field = event.target
  const value = field.value.trim()
  const fieldType = field.type
  const fieldName = field.name

  clearFieldError(event)

  // Required field validation
  if (field.hasAttribute("required") && !value) {
    showFieldError(field, "This field is required")
    return false
  }

  // Email validation
  if (fieldType === "email" && value && !isValidEmail(value)) {
    showFieldError(field, "Please enter a valid email address")
    return false
  }

  // Password validation
  if (fieldName === "password" && value && value.length < 6) {
    showFieldError(field, "Password must be at least 6 characters long")
    return false
  }

  // Confirm password validation
  if (fieldName === "confirm_password" && value) {
    const passwordField = document.querySelector('input[name="password"]')
    if (passwordField && value !== passwordField.value) {
      showFieldError(field, "Passwords do not match")
      return false
    }
  }

  return true
}

function showFieldError(field, message) {
  clearFieldError({ target: field })

  field.style.borderColor = "#EF4444"

  const errorDiv = document.createElement("div")
  errorDiv.className = "field-error"
  errorDiv.style.color = "#EF4444"
  errorDiv.style.fontSize = "0.875rem"
  errorDiv.style.marginTop = "0.25rem"
  errorDiv.textContent = message

  field.parentNode.appendChild(errorDiv)
}

function clearFieldError(event) {
  const field = event.target
  const existingError = field.parentNode.querySelector(".field-error")

  if (existingError) {
    existingError.remove()
  }

  field.style.borderColor = ""
}

function handleFormSubmit(event) {
  const form = event.target
  const inputs = form.querySelectorAll("input[required], select[required], textarea[required]")
  let isValid = true

  inputs.forEach((input) => {
    if (!validateField({ target: input })) {
      isValid = false
    }
  })

  if (!isValid) {
    event.preventDefault()
    showAlert("Please fix the errors below before submitting.", "error")

    // Scroll to first error
    const firstError = form.querySelector(".field-error")
    if (firstError) {
      firstError.scrollIntoView({ behavior: "smooth", block: "center" })
    }
  }
}

// ===== PASSWORD FEATURES =====
function initializePasswordFeatures() {
  initializePasswordToggle()
  initializePasswordStrength()
  initializePasswordMatch()
}

function togglePassword(fieldId) {
  const passwordInput = document.getElementById(fieldId)
  const toggleButton = passwordInput.nextElementSibling

  if (!passwordInput || !toggleButton) return

  if (passwordInput.type === "password") {
    passwordInput.type = "text"
    toggleButton.textContent = "🙈"
    toggleButton.setAttribute("aria-label", "Hide password")
  } else {
    passwordInput.type = "password"
    toggleButton.textContent = "👁️"
    toggleButton.setAttribute("aria-label", "Show password")
  }
}

function initializePasswordToggle() {
  const toggleButtons = document.querySelectorAll(".password-toggle button")

  toggleButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const input = this.previousElementSibling
      if (input && input.type === "password") {
        input.type = "text"
        this.textContent = "🙈"
      } else if (input) {
        input.type = "password"
        this.textContent = "👁️"
      }
    })
  })
}

function initializePasswordStrength() {
  const passwordInput = document.getElementById("password")
  if (!passwordInput) return

  passwordInput.addEventListener("input", checkPasswordStrength)
}

function checkPasswordStrength() {
  const password = document.getElementById("password").value
  const strengthBar = document.getElementById("strengthBar")
  const strengthText = document.getElementById("strengthText")

  if (!strengthBar || !strengthText) return

  let strength = 0
  let text = ""
  let className = ""

  if (password.length >= 6) strength++
  if (password.match(/[a-z]/)) strength++
  if (password.match(/[A-Z]/)) strength++
  if (password.match(/[0-9]/)) strength++
  if (password.match(/[^a-zA-Z0-9]/)) strength++

  switch (strength) {
    case 0:
    case 1:
      text = "Very weak"
      className = "strength-weak"
      break
    case 2:
      text = "Weak"
      className = "strength-weak"
      break
    case 3:
      text = "Fair"
      className = "strength-fair"
      break
    case 4:
      text = "Good"
      className = "strength-good"
      break
    case 5:
      text = "Strong"
      className = "strength-strong"
      break
  }

  strengthBar.className = "strength-fill " + className
  strengthText.textContent = text

  checkFormValidity()
}

function initializePasswordMatch() {
  const confirmPasswordInput = document.getElementById("confirm_password")
  if (!confirmPasswordInput) return

  confirmPasswordInput.addEventListener("input", checkPasswordMatch)
}

function checkPasswordMatch() {
  const password = document.getElementById("password").value
  const confirmPassword = document.getElementById("confirm_password").value
  const matchDiv = document.getElementById("passwordMatch")

  if (!matchDiv) return

  if (confirmPassword === "") {
    matchDiv.textContent = ""
    matchDiv.style.color = ""
  } else if (password === confirmPassword) {
    matchDiv.textContent = "✅ Passwords match"
    matchDiv.style.color = "#10B981"
  } else {
    matchDiv.textContent = "❌ Passwords do not match"
    matchDiv.style.color = "#EF4444"
  }

  checkFormValidity()
}

function checkFormValidity() {
  const form = document.getElementById("signupForm")
  if (!form) return

  const fullName = document.getElementById("full_name")?.value || ""
  const email = document.getElementById("email")?.value || ""
  const password = document.getElementById("password")?.value || ""
  const confirmPassword = document.getElementById("confirm_password")?.value || ""
  const terms = document.getElementById("terms")?.checked || false
  const submitBtn = document.getElementById("submitBtn")

  if (!submitBtn) return

  const isValid =
    fullName && email && password && confirmPassword && password === confirmPassword && password.length >= 6 

  submitBtn.disabled = !isValid
}

// ===== SEARCH AND SORT =====
function initializeSearchAndSort() {
  const searchInput = document.querySelector(".search-box input")
  const sortSelects = document.querySelectorAll(".sort-controls select")

  if (searchInput) {
    searchInput.addEventListener("keypress", function (e) {
      if (e.key === "Enter") {
        e.preventDefault()
        this.form.submit()
      }
    })
  }

  sortSelects.forEach((select) => {
    select.addEventListener("change", function () {
      updateUrlParameter(this.name, this.value)
    })
  })
}

function updateSort(value) {
  updateUrlParameter("sort", value)
}

function updateOrder(value) {
  updateUrlParameter("order", value)
}

function updateUrlParameter(param, value) {
  const url = new URL(window.location)
  url.searchParams.set(param, value)
  window.location = url
}

// ===== INTERACTIVE ELEMENTS =====
function initializeInteractiveElements() {
  initializeHoverEffects()
  initializeClickEffects()
  initializeFocusEffects()
}

function initializeHoverEffects() {
  // Attendee cards hover effects
  const attendeeCards = document.querySelectorAll(".attendee-card")
  attendeeCards.forEach((card) => {
    card.addEventListener("mouseenter", function () {
      this.style.transform = "translateY(-4px) scale(1.02)"
    })

    card.addEventListener("mouseleave", function () {
      this.style.transform = "translateY(0) scale(1)"
    })
  })

  // Highlight items hover effects
  const highlightItems = document.querySelectorAll(".highlight-item")
  highlightItems.forEach((item) => {
    item.addEventListener("mouseenter", function () {
      this.style.transform = "translateY(-4px) scale(1.02)"
    })

    item.addEventListener("mouseleave", function () {
      this.style.transform = "translateY(0) scale(1)"
    })
  })
}

function initializeClickEffects() {
  const buttons = document.querySelectorAll(".btn")

  buttons.forEach((button) => {
    button.addEventListener("click", function (e) {
      // Create ripple effect
      const ripple = document.createElement("span")
      const rect = this.getBoundingClientRect()
      const size = Math.max(rect.width, rect.height)
      const x = e.clientX - rect.left - size / 2
      const y = e.clientY - rect.top - size / 2

      ripple.style.width = ripple.style.height = size + "px"
      ripple.style.left = x + "px"
      ripple.style.top = y + "px"
      ripple.classList.add("ripple")

      this.appendChild(ripple)

      setTimeout(() => {
        ripple.remove()
      }, 600)
    })
  })
}

function initializeFocusEffects() {
  const inputs = document.querySelectorAll("input, select, textarea")

  inputs.forEach((input) => {
    input.addEventListener("focus", function () {
      if (this.parentElement.classList.contains("form-group")) {
        this.parentElement.style.transform = "scale(1.02)"
      }
    })

    input.addEventListener("blur", function () {
      if (this.parentElement.classList.contains("form-group")) {
        this.parentElement.style.transform = "scale(1)"
      }
    })
  })
}

// ===== ANIMATIONS =====
function initializeAnimations() {
  // Animate registration ID counter
  const idNumber = document.querySelector(".id-number")
  if (idNumber) {
    animateRegistrationId(idNumber)
  }

  // Animate stats on page load
  const statNumbers = document.querySelectorAll(".stat-number")
  statNumbers.forEach((stat) => {
    animateCounter(stat)
  })
}

function animateRegistrationId(element) {
  const finalNumber = element.textContent
  element.textContent = "#000000"

  setTimeout(() => {
    let current = 0
    const target = Number.parseInt(finalNumber.replace("#", ""))
    const increment = Math.ceil(target / 50)

    const counter = setInterval(() => {
      current += increment
      if (current >= target) {
        current = target
        clearInterval(counter)
      }
      element.textContent = "#" + current.toString().padStart(6, "0")
    }, 30)
  }, 500)
}

function animateCounter(element) {
  const target = Number.parseInt(element.textContent)
  let current = 0
  const increment = Math.ceil(target / 30)

  const counter = setInterval(() => {
    current += increment
    if (current >= target) {
      current = target
      clearInterval(counter)
    }
    element.textContent = current
  }, 50)
}

// ===== UTILITY FUNCTIONS =====
function isValidEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  return emailRegex.test(email)
}

function showAlert(message, type = "info") {
  // Remove existing alerts
  const existingAlerts = document.querySelectorAll(".alert.dynamic")
  existingAlerts.forEach((alert) => alert.remove())

  // Create new alert
  const alert = document.createElement("div")
  alert.className = `alert ${type} dynamic`
  alert.innerHTML = `
        <span>${getAlertIcon(type)}</span>
        <span>${message}</span>
        <button onclick="this.parentElement.remove()" style="margin-left: auto; background: none; border: none; font-size: 1.2rem; cursor: pointer;">×</button>
    `
  alert.style.display = "flex"
  alert.style.alignItems = "center"
  alert.style.gap = "0.5rem"

  // Insert at top of main content
  const main = document.querySelector("main") || document.body
  main.insertBefore(alert, main.firstChild)

  // Auto-remove after 5 seconds
  setTimeout(() => {
    if (alert.parentElement) {
      alert.remove()
    }
  }, 5000)

  // Scroll to alert
  alert.scrollIntoView({ behavior: "smooth", block: "center" })
}

function getAlertIcon(type) {
  switch (type) {
    case "success":
      return "✅"
    case "error":
      return "⚠️"
    case "warning":
      return "⚠️"
    case "info":
      return "ℹ️"
    default:
      return "ℹ️"
  }
}

function formatDate(dateString, format = "M j, Y g:i A") {
  const date = new Date(dateString)
  const options = {
    year: "numeric",
    month: "short",
    day: "numeric",
    hour: "numeric",
    minute: "2-digit",
    hour12: true,
  }
  return date.toLocaleDateString("en-US", options)
}

function debounce(func, wait) {
  let timeout
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout)
      func(...args)
    }
    clearTimeout(timeout)
    timeout = setTimeout(later, wait)
  }
}

function throttle(func, limit) {
  let inThrottle
  return function () {
    const args = arguments
    
    if (!inThrottle) {
      func.apply(this, args)
      inThrottle = true
      setTimeout(() => (inThrottle = false), limit)
    }
  }
}

// ===== SCROLL EFFECTS =====
window.addEventListener(
  "scroll",
  throttle(() => {
    const scrolled = window.pageYOffset
    const header = document.querySelector(".main-header")

    if (header) {
      if (scrolled > 100) {
        header.style.boxShadow = "0 8px 25px rgba(15, 118, 110, 0.3)"
      } else {
        header.style.boxShadow = "0 4px 12px rgba(15, 118, 110, 0.2)"
      }
    }
  }, 100),
)

// ===== KEYBOARD NAVIGATION =====
document.addEventListener("keydown", (e) => {
  // ESC key to close mobile menu
  if (e.key === "Escape" && mobileMenuOpen) {
    toggleMobileMenu()
  }

  // Enter key on buttons
  if (e.key === "Enter" && e.target.classList.contains("btn")) {
    e.target.click()
  }
})

// ===== PRINT FUNCTIONALITY =====
function printPage() {
  window.print()
}

// ===== LOCAL STORAGE HELPERS =====
function saveToLocalStorage(key, value) {
  try {
    localStorage.setItem(key, JSON.stringify(value))
  } catch (e) {
    console.warn("Could not save to localStorage:", e)
  }
}

function getFromLocalStorage(key) {
  try {
    const item = localStorage.getItem(key)
    return item ? JSON.parse(item) : null
  } catch (e) {
    console.warn("Could not read from localStorage:", e)
    return null
  }
}

// ===== FORM AUTO-SAVE (Optional) =====
function initializeAutoSave() {
  const forms = document.querySelectorAll("form[data-autosave]")

  forms.forEach((form) => {
    const formId = form.id || "form_" + Date.now()
    const inputs = form.querySelectorAll("input, select, textarea")

    // Load saved data
    const savedData = getFromLocalStorage("autosave_" + formId)
    if (savedData) {
      inputs.forEach((input) => {
        if (savedData[input.name] && input.type !== "password") {
          input.value = savedData[input.name]
        }
      })
    }

    // Save on input
    const debouncedSave = debounce(() => {
      const formData = {}
      inputs.forEach((input) => {
        if (input.type !== "password") {
          formData[input.name] = input.value
        }
      })
      saveToLocalStorage("autosave_" + formId, formData)
    }, 1000)

    inputs.forEach((input) => {
      input.addEventListener("input", debouncedSave)
    })

    // Clear on successful submit
    form.addEventListener("submit", () => {
      localStorage.removeItem("autosave_" + formId)
    })
  })
}

// ===== ERROR HANDLING =====
window.addEventListener("error", (e) => {
  console.error("JavaScript Error:", e.error)
  // You could send this to a logging service
})

window.addEventListener("unhandledrejection", (e) => {
  console.error("Unhandled Promise Rejection:", e.reason)
  // You could send this to a logging service
})

// ===== EXPORT FOR TESTING =====
if (typeof module !== "undefined" && module.exports) {
  module.exports = {
    isValidEmail,
    formatDate,
    debounce,
    throttle,
  }
}
