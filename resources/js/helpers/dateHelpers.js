/**
 * Format a date for API requests to ensure consistent format
 * @param {Date|string} date - The date to format
 * @returns {string} - Formatted date string
 */
export function formatDateForApi(date) {
  if (!date) return null;

  // If it's already a string, try to parse it
  if (typeof date === 'string') {
    date = new Date(date);
  }

  // Check if date is valid
  if (isNaN(date.getTime())) {
    console.error('Invalid date provided to formatDateForApi:', date);
    return null;
  }

  // Format as ISO string which is compatible with Laravel's datetime parsing
  return date.toISOString();
}

/**
 * Format a date for display in the UI
 * @param {Date|string} date - The date to format
 * @returns {string} - Formatted date string for display
 */
export function formatDateForDisplay(date) {
  if (!date) return '';

  // If it's a string, parse it
  if (typeof date === 'string') {
    date = new Date(date);
  }

  // Check if date is valid
  if (isNaN(date.getTime())) {
    console.error('Invalid date provided to formatDateForDisplay:', date);
    return '';
  }

  // Format for display (customize as needed)
  return date.toLocaleString();
}
