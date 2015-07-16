#!/usr/bin/env python

import os
import re
import sys

from codecs import open

try:
    from setuptools import setup
except ImportError:
    from distutils.core import setup

if sys.argv[-1] == 'publish':
    os.system('python setup.py sdist upload')
    sys.exit()

packages = [
    'relateiq',
]

requires = [
    'requests==2.6.0',
    'nameparser==0.3.4',
    'validate_email==1.3',
    'pytz==2015.2',
]

setup(
    name='relateiq',
    version='0.0.1',
    description='Api sdk for api.relateiq.com',
    long_description='',
    author='relateiq',
    url='http://api.relateiq.com',
    packages=packages,
    install_requires=requires,
    license='Apache 2.0',
    zip_safe=False,
    classifiers=(
        'Development Status :: 1 - Alpha',
        'Intended Audience :: Developers',
        'Natural Language :: English',
        'License :: OSI Approved :: Apache Software License',
        'Programming Language :: Python',
        'Programming Language :: Python :: 2.7',
        'Programming Language :: Python :: 3.4'
    ),
)
