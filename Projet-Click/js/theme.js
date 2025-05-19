function switchTheme() {
    const themeLink = document.getElementById("theme-link");
    const currentTheme = themeLink.getAttribute("href");
    const newTheme = currentTheme === "style.css" ? "style-dark.css" : "style.css";
    themeLink.setAttribute("href", newTheme);
    document.cookie = "theme=" + newTheme + "; path=/; max-age=" + 30*24*60*60;
}

function getCookie(name) {
    const value = "; " + document.cookie;
    const parts = value.split("; " + name + "=");
    if (parts.length === 2) return parts.pop().split(";").shift();
}

window.addEventListener("DOMContentLoaded", () => {
    const savedTheme = getCookie("theme");
    if (savedTheme) {
        document.getElementById("theme-link").setAttribute("href", savedTheme);
    }
});