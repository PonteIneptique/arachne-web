SELECT 
    time_log,
    COALESCE(lf.id_lemma, le.id_lemma),
    text_lemma,
    lf.id_sentence,
    s.text_sentence
FROM 
    log l
    LEFT JOIN lemma_has_form lf ON ((lf.id_sentence = l.target_log AND table_log = 'sentence') OR (l.target_log = lf.id_lemma_has_form AND table_log='lemma_has_form'))
    LEFT JOIN lemma le ON ((le.id_lemma = l.target_log AND l.table_log = "lemma") OR (lf.id_lemma = le.id_lemma AND l.table_log = "lemma_has_form"))
    LEFT JOIN sentence s ON ((s.id_sentence = l.target_log AND table_log = 'sentence') OR (lf.id_sentence = s.id_sentence AND table_log='lemma_has_form'))
WHERE
    l.table_log != "user"
    AND l.id_user = 1
GROUP BY
    time_log