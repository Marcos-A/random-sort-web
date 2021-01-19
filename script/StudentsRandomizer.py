#!/usr/bin/env python3
# -*- coding: utf-8 -*-

import os
import pandas as pd
import random
from sys import argv

SOURCE_FILENAME = argv[1]
RESULT_FILENAME = "randomly_sorted_students.txt"
TMP_DIR = '../script/tmp'

def get_students_names_to_list():
    students_file = pd.read_csv(SOURCE_FILENAME, header=None)
    return students_file.iloc[:,0].tolist()


def randomize_students_list(students_list):
    return random.sample(students_list, len(students_list))


def export_randomly_sorted_students_to_txt(students_list):
    txt_file = open(os.path.join(TMP_DIR, RESULT_FILENAME), "w")
    i = 1
    for student in students_list:
        numbered_randomly_sorted_student = str(i) + ". " + student
        txt_file.write(numbered_randomly_sorted_student + "\n")
        i = i+1
    txt_file.close()


def print_randomly_sorted_students():
    txt_file = open(os.path.join(TMP_DIR, RESULT_FILENAME), "r")
    txt_file_lines = txt_file.read().splitlines()
    for line in txt_file_lines:
        print(line)
    txt_file.close()


"""def remove_tmp_files
Descripció: Suprimeix tots els butlletins PDF individuals.
Entrada:    Cap.
Sortida:    Cap.
"""
def remove_tmp_files():
    pdf_files_list = [os.path.join(os.getcwd(),TMP_DIR, f) for f
                      in os.listdir(os.path.join(os.getcwd(),TMP_DIR))
                      if f.split('.')[1]=='pdf']

    for file in pdf_files_list:
        os.remove(file)

if __name__ == "__main__":
    students_list = get_students_names_to_list()
    randomized_students_list = randomize_students_list(students_list)
    export_randomly_sorted_students_to_txt(randomized_students_list)
    print_randomly_sorted_students()
    # remove_tmp_files()