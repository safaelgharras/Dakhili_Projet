-- Synchronize seuil with min_average for all institutions
UPDATE institutions SET seuil = min_average WHERE seuil IS NULL OR seuil = 0;

-- Ensure all institutions have at least 'Maroc' as city if city is null
UPDATE institutions SET city = 'Maroc' WHERE city IS NULL;
