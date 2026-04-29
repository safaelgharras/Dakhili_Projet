-- Map city names to ville_id
UPDATE institutions i
JOIN villes v ON i.city = v.nom
SET i.ville_id = v.id
WHERE i.ville_id IS NULL;

-- Manual fix for common variations if any (e.g., Fes vs Fès)
UPDATE institutions SET ville_id = 4 WHERE city LIKE 'Fes%' AND ville_id IS NULL;
UPDATE institutions SET ville_id = 1 WHERE city LIKE 'Casa%' AND ville_id IS NULL;
