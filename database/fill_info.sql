-- Fill missing Diplôme and Durée based on institution type
UPDATE institutions SET diplome = 'Ingénieur d\'État', duree_etudes = '5 ans' WHERE (type = 'Engineering' OR name LIKE '%ENSA%' OR name LIKE '%EMI%') AND (diplome IS NULL OR diplome = 'Diplôme' OR diplome = '');

UPDATE institutions SET diplome = 'Master en Management', duree_etudes = '5 ans' WHERE (type = 'Business' OR name LIKE '%ENCG%' OR name LIKE '%ISCAE%') AND (diplome IS NULL OR diplome = 'Diplôme' OR diplome = '');

UPDATE institutions SET diplome = 'DUT / Licence Pro', duree_etudes = '2 ans' WHERE (type = 'Technical' OR name LIKE '%EST%') AND (diplome IS NULL OR diplome = 'Diplôme' OR diplome = '');

UPDATE institutions SET diplome = 'Licence / Master', duree_etudes = '3-5 ans' WHERE (type IN ('Science', 'University', 'Education') OR name LIKE '%FST%' OR name LIKE '%Faculté%' OR name LIKE '%ENS%') AND (diplome IS NULL OR diplome = 'Diplôme' OR diplome = '');

UPDATE institutions SET diplome = 'Attestation CPGE', duree_etudes = '2 ans' WHERE (type = 'Preparatory' OR name LIKE '%CPGE%') AND (diplome IS NULL OR diplome = 'Diplôme' OR diplome = '');

UPDATE institutions SET diplome = 'Bachelor / Master', duree_etudes = '3-5 ans' WHERE type = 'Private' AND (diplome IS NULL OR diplome = 'Diplôme' OR diplome = '');

-- Final fallback for anything else still empty
UPDATE institutions SET diplome = 'Diplôme National' WHERE diplome IS NULL OR diplome = 'Diplôme' OR diplome = '';
UPDATE institutions SET duree_etudes = '3-5 ans' WHERE duree_etudes IS NULL OR duree_etudes = '--' OR duree_etudes = '';

-- Also ensure requirements are not empty
UPDATE institutions SET requirements = 'Baccalauréat requis' WHERE requirements IS NULL OR requirements = '';
